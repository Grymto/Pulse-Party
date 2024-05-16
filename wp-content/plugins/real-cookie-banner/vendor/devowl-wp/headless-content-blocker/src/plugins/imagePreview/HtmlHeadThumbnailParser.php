<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview;

use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\FastHtmlTag;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\match\TagAttributeMatch;
use DevOwl\RealCookieBanner\Vendor\DevOwl\FastHtmlTag\finder\TagAttributeFinder;
use Requests;
use Requests_Response;
use SimpleXMLElement;
use WpOrg\Requests\Response;
/**
 * The head parser extracts links and meta from HTML responses.
 * @internal
 */
class HtmlHeadThumbnailParser extends FastHtmlTag
{
    const META_PROPERTIES_IMAGE = ['og:image', 'og:image:url', 'og:image:secure_url', 'twitter:image', 'twitter:image:src', 'lp:image'];
    const META_PROPERTIES_TITLE = ['og:title', 'twitter:title', 'lp:title', 'dcterms.title', 'article:title', 'headline', 'article.headline', 'parsely-title'];
    const XML_XPATH_IMAGE = ['//itunes:image/@href'];
    const XML_XPATH_TITLE = ['//channel/title'];
    const LINK_REL_IMAGE = ['image_src'];
    /**
     * Result of the last `$headParser` execution.
     *
     * @var array
     */
    private $headParserResult = [];
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $finder = new TagAttributeFinder(['meta', 'link'], ['rel', 'property']);
        $finder->addCallback(function ($match) {
            /**
             * TagAttributeMatch
             *
             * @var TagAttributeMatch
             */
            $match = $match;
            $tag = $match->getTag();
            switch ($tag) {
                case 'meta':
                    if ($match->hasAttribute('property') && $match->hasAttribute('content')) {
                        $property = $match->getAttribute('property');
                        $content = $match->getAttribute('content');
                        if (!isset($this->headParserResult['thumbnail_url'])) {
                            foreach (self::META_PROPERTIES_IMAGE as $expectedProperty) {
                                if ($property === $expectedProperty) {
                                    $this->headParserResult['thumbnail_url'] = $content;
                                    return;
                                }
                            }
                        }
                        if (!isset($this->headParserResult['title'])) {
                            foreach (self::META_PROPERTIES_TITLE as $expectedProperty) {
                                if ($property === $expectedProperty) {
                                    $this->headParserResult['title'] = $content;
                                    return;
                                }
                            }
                        }
                    }
                    break;
                case 'link':
                    if ($match->hasAttribute('rel') && $match->hasAttribute('href')) {
                        $rel = $match->getAttribute('rel');
                        $href = $match->getAttribute('href');
                        if (!isset($this->headParserResult['thumbnail_url'])) {
                            foreach (self::LINK_REL_IMAGE as $expectedRel) {
                                if ($rel === $expectedRel) {
                                    $this->headParserResult['thumbnail_url'] = $href;
                                    return;
                                }
                            }
                        }
                    }
                    break;
                default:
                    break;
            }
        });
        $this->addFinder($finder);
    }
    /**
     * Send multiple HTTP requests simultaneously and return a map of URL to image preview URL and additional data.
     * If there could not be found an image, the requested URL does not exist in the result array.
     *
     * @param string[] $urls
     */
    public function extractFromUrls($urls)
    {
        // Avoid issues where mbstring.func_overload is enabled.
        \mbstring_binary_safe_encoding();
        // TODO: make framework-agnostic, e.g. curl_multi_init
        $responses = Requests::request_multiple(\array_map(function ($u) {
            return ['url' => $u];
        }, $urls), ['timeout' => 5, 'type' => 'GET', 'redirects' => 5]);
        $result = [];
        foreach ($responses as $key => $response) {
            if ($response instanceof Requests_Response || $response instanceof Response) {
                $url = $urls[$key];
                $contentType = $response->headers->getValues('content-type');
                $contentType = $contentType === null ? null : $contentType[0];
                $thumbnailUrl = $this->convertUrlToThumbnailFromResponse($url, $contentType, $response->body);
                if ($thumbnailUrl !== \false) {
                    $result[$url] = $thumbnailUrl;
                }
            }
            // Currently, we ignore all other requests as then there is simply no preview image
        }
        \reset_mbstring_encoding();
        return $result;
    }
    /**
     * Use the power of `SimpleXMLElement` and XPath to extract title and images from a given XML body.
     *
     * @param string $body
     */
    protected function convertUrlToThumbnailFromXml($body)
    {
        $xml = new SimpleXMLElement($body);
        $result = [];
        foreach (self::XML_XPATH_TITLE as $xpath) {
            $xpathRes = $this->extractXpathResToSingleValue($xml->xpath($xpath));
            if (!empty($xpathRes)) {
                $result['title'] = $xpathRes;
                break;
            }
        }
        foreach (self::XML_XPATH_IMAGE as $xpath) {
            $xpathRes = $this->extractXpathResToSingleValue($xml->xpath($xpath));
            if (!empty($xpathRes)) {
                $result['thumbnail_url'] = $xpathRes;
                break;
            }
        }
        return $result;
    }
    /**
     * Check if a given `->xpath()` result is present and extract a single value.
     *
     * @param static[]|false|null $result
     */
    protected function extractXpathResToSingleValue($result)
    {
        if (!empty($result) && \is_array($result) && \count($result) > 0 && \is_object($result[0])) {
            $result = (array) $result[0];
            if (isset($result[0]) && \is_string($result[0])) {
                // Single string value
                return $result[0];
            } elseif (isset($result['@attributes'])) {
                // Single property value
                $result = \array_values($result['@attributes'])[0];
                return $result;
            }
        }
        return null;
    }
    /**
     * Pass in some response infos about a requested URL and we will try to detect a thumbnail URL
     * and other infos like title and description for you.
     *
     * @param string $requestedUrl
     * @param string $contentType
     * @param string $body
     * @return array|false
     */
    protected function convertUrlToThumbnailFromResponse($requestedUrl, $contentType, $body)
    {
        if (\strpos($contentType, 'image/') === 0) {
            return ['thumbnail_url' => $requestedUrl];
        } elseif (\strpos($contentType, 'text/html') === 0 && \strpos($body, '</head>') !== \false) {
            $head = \explode('</head>', $body, 2)[0];
            $this->headParserResult = [];
            $this->modifyAny($head);
            if (isset($this->headParserResult['thumbnail_url'])) {
                return $this->headParserResult;
            }
        } elseif (\strpos($contentType, 'application/xml') === 0 && \class_exists('SimpleXMLElement')) {
            $res = $this->convertUrlToThumbnailFromXml($body);
            if (isset($res['thumbnail_url'])) {
                return $res;
            }
        }
        return \false;
    }
}
