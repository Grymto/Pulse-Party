<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\AbstractMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\Utils as FastHtmlTagUtils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractPlugin;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AttributesHelper;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Utils;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\BlockedResult;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\Constants;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\matcher\AbstractMatcher;
use DevOwl\RealCookieBanner\Vendor\Embera\Embera;
use Exception;
/**
 * Detects, if there could be created an image preview for a given URL, downloads it and appends
 * it as a custom attribute to the blocked HTML tag so the visual content blocker could create something cool.
 *
 * - You **need** to pass a `setCache` to the plugin instance, otherwise the mechanism does not track any URLs. The
 *   purpose of the headless content blocker is to block external URLs, and we do not want to use an external URL as image preview!
 * - Your `AbstractBlockable` **needs** to implement `ImagePreviewBlockable` interface to determine if thumbnails
 *   should be loaded for the blocked content!
 * @internal
 */
class ImagePreview extends AbstractPlugin
{
    const HTML_ATTRIBUTE_TO_FETCH_URL_FROM = 'hcb-fetch-image-from';
    const HTML_ATTRIBUTE_TO_FETCH_URL_FROM_ALTERNATIVE = 'hcb-fetch-image-from-alt';
    const SKIP_EMBED_URL_EXTENSIONS = ['js', 'css', 'zip', 'pdf'];
    /**
     * A list of possible link attributes.
     *
     * @var string[]
     */
    private $attributesToCheckForUrl = [];
    /**
     * HtmlHeadThumbnailParser.
     *
     * @var HtmlHeadThumbnailParser
     */
    private $htmlHeadThumbnailParser;
    /**
     * ImagePreviewCache.
     *
     * @var ImagePreviewCache
     */
    private $cache;
    /**
     * Init.
     */
    public function init()
    {
        $cb = $this->getHeadlessContentBlocker();
        $attr = \array_values(\array_unique(Utils::array_flatten(\array_column(\array_values($cb->getTagAttributeMap()), 'attr'))));
        $transformedAttr = \array_map([AttributesHelper::class, 'transformAttribute'], $attr);
        $this->attributesToCheckForUrl = \array_merge($attr, $transformedAttr);
        $this->htmlHeadThumbnailParser = new HtmlHeadThumbnailParser();
    }
    /**
     * Special case: Read attribute of the original embed URL if it got passed via a custom attribute.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function checkResult($result, $matcher, $match)
    {
        $attr = $match->getAttribute(self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM);
        if ($attr !== null && $result->isBlocked()) {
            $result->setData(self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM, $attr);
        }
        return $result;
    }
    /**
     * See `AbstractPlugin`.
     *
     * @param BlockedResult $result
     * @param AbstractMatcher $matcher
     * @param AbstractMatch $match
     */
    public function blockedMatch($result, $matcher, $match)
    {
        $foundUrl = $result->getData(self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM);
        // Search by known attributes to check (higher priority)
        if ($foundUrl === null) {
            foreach ($this->attributesToCheckForUrl as $attr) {
                $validUrl = $this->isValidEmbedUrl($match->getAttribute($attr));
                if ($validUrl) {
                    $foundUrl = $validUrl;
                    break;
                }
            }
        }
        // Search by all attributes
        if ($foundUrl === null) {
            foreach ($match->getAttributes() as $attr => $value) {
                if (!\in_array($attr, $this->attributesToCheckForUrl, \true)) {
                    $validUrl = $this->isValidEmbedUrl($value);
                    if ($validUrl) {
                        $foundUrl = $validUrl;
                        break;
                    }
                }
            }
        }
        // Search by special cases
        $specialMatchUrl = $this->findUrlForSpecialMatch($match);
        if ($specialMatchUrl !== null) {
            $foundUrl = $specialMatchUrl;
        }
        if (!empty($foundUrl)) {
            $blockable = $result->getFirstBlocked();
            // Check if URL is already masked with the blocker ID
            if (Utils::startsWith($foundUrl, '{')) {
                $val = \json_decode($foundUrl, ARRAY_A);
                $foundUrl = $val['url'];
            }
            if ($blockable instanceof ImagePreviewBlockable && $blockable->downloadImagePreviewFor($foundUrl, $result, $match)) {
                // Set the HTML attribute for our blocked item, so our `afterSetup` callback can retrieve it
                // and send requests in bulk.
                $match->setAttribute(self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM, \json_encode([
                    'blockable' => $blockable->getBlockerId(),
                    'url' => Thumbnail::normalizeEmbedUrl($foundUrl),
                    // Allow to override aspect ratio from `width` and `height` HTML attributes
                    'aspectRatio' => \is_numeric($match->getAttribute('width')) && \is_numeric($match->getAttribute('height')) ? \intval($match->getAttribute('height')) / \intval($match->getAttribute('width')) * 100 : null,
                ]));
            }
            $result->setData(self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM, null);
        }
    }
    /**
     * Find an embed URL for a special match.
     *
     * @param AbstractMatch $match
     */
    protected function findUrlForSpecialMatch($match)
    {
        $tag = $match->getTag();
        $class = $match->getAttribute(AttributesHelper::transformAttribute('class'), $match->getAttribute('class', ''));
        // Imgur
        if ($tag === 'blockquote' && $class === 'imgur-embed-pub' && $match->hasAttribute('data-id')) {
            return \sprintf('https://imgur.com/%s', $match->getAttribute('data-id'));
        }
        // https://github.com/paulirish/lite-youtube-embed
        if (\in_array($tag, ['consent-lite-youtube', 'lite-youtube'], \true)) {
            $videoId = $match->getAttribute(AttributesHelper::transformAttribute('videoid'), $match->getAttribute('videoid', ''));
            if (!empty($videoId)) {
                return \sprintf('https://youtube.com/watch/?v=%s', $videoId);
            }
        }
        // https://github.com/luwes/lite-vimeo-embed
        if (\in_array($tag, ['consent-lite-vimeo', 'lite-vimeo'], \true)) {
            $videoId = $match->getAttribute(AttributesHelper::transformAttribute('videoid'), $match->getAttribute('videoid', ''));
            if (!empty($videoId)) {
                return \sprintf('https://vimeo.com/%s', $videoId);
            }
        }
        return null;
    }
    /**
     * Check if a given embed URL is valid.
     *
     * @param string $embedUrl
     * @param boolean $respectSpecialCases
     */
    protected function isValidEmbedUrl($embedUrl, $respectSpecialCases = \true)
    {
        if (empty($embedUrl)) {
            return \false;
        }
        // Check if the attribute is a JSON string
        if (Utils::startsWith($embedUrl, '{')) {
            $decoded = \html_entity_decode($embedUrl);
            $json = FastHtmlTagUtils::isJson($decoded);
            if (\is_array($json)) {
                $foundUrl = null;
                \array_walk_recursive($json, function ($item) use(&$foundUrl) {
                    if (\is_string($item) && $foundUrl === null) {
                        $result = $this->isValidEmbedUrl($item);
                        if ($result !== \false) {
                            $foundUrl = $result;
                        }
                    }
                });
                if ($foundUrl !== null) {
                    return $foundUrl;
                }
            }
        }
        // Check if the attribute has slashed content with a potential URL
        // Example: onclick='return {"player_id":"vimeo-m206","player_api":"","player_html":"&lt;iframe title=\"Vimeo video player\" src=\"https:\/\/player.vimeo.com\/video\/3333333?byline=0&amp;title=0&amp;autoplay=1\" frameborder=\"0\" allow=\"autoplay; fullscreen\" allowfullscreen loading=\"lazy\"&gt;&lt;\/iframe&gt;"}'
        // See https://regex101.com/r/J8GPGb/5
        if ($respectSpecialCases) {
            $stripSlashes = \stripslashes($embedUrl);
            if (\preg_match('/(https?:\\/\\/[^\\s"\'\\)\\(]+)/m', $stripSlashes, $match)) {
                $result = $this->isValidEmbedUrl($match[0], \false);
                if ($result !== \false) {
                    return $result;
                }
            }
        }
        $urlToCheck = FastHtmlTagUtils::setUrlSchema($embedUrl, 'https');
        if (\filter_var($urlToCheck, \FILTER_VALIDATE_URL)) {
            $path = \parse_url($urlToCheck, \PHP_URL_PATH);
            if ($path !== null) {
                $extension = \pathinfo($path, \PATHINFO_EXTENSION);
                if (!\in_array($extension, self::SKIP_EMBED_URL_EXTENSIONS, \true)) {
                    return $urlToCheck;
                }
            }
        }
        return \false;
    }
    // Documented in AbstractPlugin
    public function afterSetup()
    {
        $this->getHeadlessContentBlocker()->addCallback(function ($html) {
            if ($this->cache === null) {
                return $html;
            }
            // @see https://regex101.com/r/J48TeA/1
            $regex = \sprintf('/(%s="[^"]+")/m', self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM);
            // First, extract all URLs so we can batch them
            if (\preg_match_all($regex, $html, $urlsToFetch, \PREG_SET_ORDER, 0)) {
                $urlsToFetch = \array_column($urlsToFetch, 1);
                $urlToThumbnail = [];
                // Read valid URLs and use them as matches and also create requests
                foreach ($urlsToFetch as &$val) {
                    $val = FastHtmlTagUtils::parseHtmlAttributes($val);
                    $val = $val[self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM] ?? null;
                    if ($val !== null) {
                        $val = FastHtmlTagUtils::isJson($val);
                        if ($val !== \false) {
                            $blockerId = $val['blockable'];
                            $url = $val['url'];
                            if (\is_numeric($blockerId)) {
                                $blockable = $this->getHeadlessContentBlocker()->getBlockableById(\intval($blockerId));
                                if ($blockable !== null) {
                                    $thumbnailInstance = new Thumbnail($url, $blockable);
                                    if (isset($val['aspectRatio'])) {
                                        $thumbnailInstance->setForceRatio($val['aspectRatio']);
                                    }
                                    $urlToThumbnail[$url] = $thumbnailInstance;
                                }
                            }
                        }
                    }
                }
                $this->fetchMetadata($urlToThumbnail);
                $this->cache->allowance($urlToThumbnail);
                // Do the replacement
                return \preg_replace_callback($regex, function ($m) use($urlToThumbnail) {
                    $embedUrl = FastHtmlTagUtils::parseHtmlAttributes($m[0]);
                    $embedUrl = $embedUrl[self::HTML_ATTRIBUTE_TO_FETCH_URL_FROM] ?? null;
                    if ($embedUrl === null) {
                        return '';
                    }
                    $embedUrl = FastHtmlTagUtils::isJson($embedUrl);
                    if ($embedUrl === \false) {
                        return '';
                    }
                    $embedUrl = $embedUrl['url'];
                    /**
                     * Thumbnail
                     *
                     * @var Thumbnail
                     */
                    $thumbnail = $urlToThumbnail[$embedUrl] ?? null;
                    if ($thumbnail !== null && $thumbnail->getAllowance() !== Thumbnail::ALLOWANCE_NO) {
                        $useAttribute = $thumbnail->getAllowance() === Thumbnail::ALLOWANCE_SUGGEST ? Constants::HTML_ATTRIBUTE_THUMBNAIL_SUGGESTION : Constants::HTML_ATTRIBUTE_THUMBNAIL;
                        return FastHtmlTagUtils::htmlAttributes([$useAttribute => \json_encode($this->getWriteJson($thumbnail))]);
                    }
                    // Not found any thumbnail, We never need this attribute on our frontend again
                    return '';
                }, $html);
            }
            return $html;
        });
    }
    /**
     * Create the result for the HTML attribute which our unblocker can consume.
     *
     * @param Thumbnail $thumbnail
     */
    public function getWriteJson($thumbnail)
    {
        $writeJson = ['embedId' => $thumbnail->getId(), 'fileMd5' => $thumbnail->getMd5File(), 'url' => $thumbnail->getCacheUrl(), 'title' => $thumbnail->getTitle(), 'width' => $thumbnail->getWidth(), 'height' => $thumbnail->getHeight()];
        if ($thumbnail->getForceRatio() > 0) {
            $writeJson['forceRatio'] = $thumbnail->getForceRatio();
        }
        return $writeJson;
    }
    /**
     * Use multiple mechanism to extract a thumbnail from any URL. It returns an map of URL => thumbnail,
     * and if a thumbnail got not found it is no longer part of the returned map.
     *
     * Mechanism:
     *
     * 1. Cache
     * 2. Oembed
     * 3. HTML Head Parser and extract thumbnail URL from known meta tags
     *
     * @param Thumbnail[] $thumbnails Key needs to be the embed URL
     */
    public function fetchMetadata(&$thumbnails)
    {
        // Method: Cache
        $this->cache->get($thumbnails);
        // Method: oEmbed
        $notFoundEmbedUrls = [];
        foreach ($thumbnails as $embedUrl => $thumbnail) {
            if ($thumbnail->isAwaitingThumbnailUrl()) {
                $notFoundEmbedUrls[] = $embedUrl;
            }
        }
        if (\count($notFoundEmbedUrls) > 0) {
            try {
                $embera = new Embera();
                $fetched = $embera->getUrlData($notFoundEmbedUrls);
                foreach ($notFoundEmbedUrls as $embedUrl) {
                    if (isset($fetched[$embedUrl]) && isset($fetched[$embedUrl]['thumbnail_url'])) {
                        // We found an oEmbed...
                        $thumbnail = $thumbnails[$embedUrl];
                        $result = $fetched[$embedUrl];
                        $thumbnail->setThumbnailUrl($result['thumbnail_url'], $result['provider_name'] ?? null);
                        if (!empty($result['title'])) {
                            $thumbnail->setTitle($result['title']);
                        }
                    }
                }
            } catch (Exception $e) {
                // Currently, just ignore and try the next method
            }
        }
        // Method: Simple `<head` parser and extract HTML meta
        $notFoundEmbedUrls = [];
        foreach ($thumbnails as $embedUrl => $thumbnail) {
            if ($thumbnail->isAwaitingThumbnailUrl() || empty($thumbnail->getTitle())) {
                $notFoundEmbedUrls[] = $embedUrl;
            }
        }
        if (\count($notFoundEmbedUrls) > 0) {
            $fetched = $this->htmlHeadThumbnailParser->extractFromUrls($notFoundEmbedUrls);
            foreach ($notFoundEmbedUrls as $embedUrl) {
                if (isset($fetched[$embedUrl])) {
                    $thumbnail = $thumbnails[$embedUrl];
                    $result = $fetched[$embedUrl];
                    if (empty($thumbnail->getThumbnailUrl())) {
                        $thumbnail->setThumbnailUrl($result['thumbnail_url']);
                    }
                    if (!empty($result['title']) && empty($thumbnail->getTitle())) {
                        $thumbnail->setTitle($result['title']);
                    }
                }
            }
        }
        // Remove files with error and non-existing thumbnail
        foreach ($thumbnails as $embedUrl => $thumbnail) {
            if ($thumbnail->isAwaitingThumbnailUrl() || !empty($thumbnail->getError())) {
                unset($thumbnails[$embedUrl]);
            }
        }
        $nonCached = [];
        foreach ($thumbnails as $thumbnail) {
            if (empty($thumbnail->getCacheUrl())) {
                $nonCached[] = $thumbnail;
            }
        }
        if (\count($nonCached) > 0) {
            $this->cache->set($nonCached);
        }
        // Remove files with error which could have been occurred while caching
        foreach ($thumbnails as $embedUrl => $thumbnail) {
            if (!empty($thumbnail->getError())) {
                unset($thumbnails[$embedUrl]);
            }
        }
    }
    /**
     * Setter.
     *
     * @param ImagePreviewCache $cache
     * @codeCoverageIgnore
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCache()
    {
        return $this->cache;
    }
}
