<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\imagePreview;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
/**
 * A thumbnail is describing a found embed URL.
 * @internal
 */
class Thumbnail
{
    const ALLOWANCE_NO = 'no';
    const ALLOWANCE_PUBLIC = 'public';
    const ALLOWANCE_SUGGEST = 'suggest';
    private $embedUrl;
    private $blockable;
    private $id;
    /**
     * The thumbnail MD5 file checksum. This is needed to differ between multiple downloaded
     * images of the same embed file. E.g. a YouTube preview image to changed.
     *
     * @var string
     */
    private $md5File;
    /**
     * Thumbnail URL.
     *
     * @var string
     */
    private $thumbnailUrl;
    /**
     * Alternative thumbnail URLs for `$thumbnailUrl`. If a thumbnail URL does not exist, try
     * another one.
     *
     * @var string
     */
    private $alternativeThumbnail;
    /**
     * Optional title to this thumbnail.
     *
     * @var string
     */
    private $title;
    /**
     * Thumbnail width in px.
     *
     * @var int
     */
    private $width;
    /**
     * Thumbnail height in px.
     *
     * @var int
     */
    private $height;
    /**
     * Force ratio by percent.
     *
     * @var float
     */
    private $forceRatio;
    /**
     * Cached thumbnail URL.
     *
     * @var string
     */
    private $cacheUrl;
    /**
     * Error message.
     *
     * @var string
     */
    private $error;
    /**
     * See class constants starting with `ALLOWANCE`.
     *
     * @var int
     */
    private $allowance = self::ALLOWANCE_NO;
    /**
     * C'tor.
     *
     * @param string $embedUrl
     * @param AbstractBlockable|ImagePreviewBlockable $blockable must be a subclass of AbstractBlockable and also implements ImagePreviewBlockable
     */
    public function __construct($embedUrl, $blockable)
    {
        $this->embedUrl = $embedUrl;
        $this->id = \md5($embedUrl);
        $this->blockable = $blockable;
    }
    /**
     * Check if this thumbnail is still awaiting another fetch mechanism (e.g. oEmbed or HTML head parser)
     * to obtain the thumbnail URL.
     */
    public function isAwaitingThumbnailUrl()
    {
        if (!empty($this->getCacheUrl())) {
            return \false;
        }
        return empty($this->getError()) && empty($this->getThumbnailUrl());
    }
    /**
     * Setter.
     *
     * @param string $md5File
     * @codeCoverageIgnore
     */
    public function setMd5File($md5File)
    {
        $this->md5File = $md5File;
    }
    /**
     * Setter.
     *
     * @param string $thumbnailUrl
     * @param string $provider
     * @codeCoverageIgnore
     */
    public function setThumbnailUrl($thumbnailUrl, $provider = null)
    {
        if ($provider !== null) {
            switch (\strtolower($provider)) {
                case 'youtube':
                    $this->alternativeThumbnail = $thumbnailUrl;
                    $thumbnailUrl = \str_replace(['hqdefault'], ['sddefault'], $thumbnailUrl);
                    // Force the ratio to be 16/9 as YouTube adds black bars on top / bottom sides
                    if ($this->forceRatio === null) {
                        $this->setForceRatio(9 / 16 * 100);
                    }
                    break;
                default:
                    break;
            }
        }
        $this->thumbnailUrl = $thumbnailUrl;
    }
    /**
     * Setter.
     *
     * @param string $title
     * @codeCoverageIgnore
     */
    public function setTitle($title)
    {
        $this->title = self::normalizeTitle($title);
    }
    /**
     * Setter.
     *
     * @param int $width
     * @codeCoverageIgnore
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }
    /**
     * Setter.
     *
     * @param int $height
     * @codeCoverageIgnore
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }
    /**
     * Setter.
     *
     * @param float $ratio
     * @codeCoverageIgnore
     */
    public function setForceRatio($ratio)
    {
        $this->forceRatio = $ratio;
    }
    /**
     * Setter.
     *
     * @param string $cacheUrl
     * @codeCoverageIgnore
     */
    public function setCacheUrl($cacheUrl)
    {
        $this->cacheUrl = $cacheUrl;
    }
    /**
     * Setter.
     *
     * @param string $error
     * @codeCoverageIgnore
     */
    public function setError($error)
    {
        $this->error = $error;
    }
    /**
     * Setter.
     *
     * @param int $allowance
     * @codeCoverageIgnore
     */
    public function setAllowance($allowance)
    {
        $this->allowance = $allowance;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getEmbedUrl()
    {
        return $this->embedUrl;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBlockable()
    {
        return $this->blockable;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getMd5File()
    {
        return $this->md5File;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getThumbnailUrl()
    {
        return $this->thumbnailUrl;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAlternativeThumbnailUrl()
    {
        return $this->alternativeThumbnail;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getWidth()
    {
        return $this->width;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getHeight()
    {
        return $this->height;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getForceRatio()
    {
        return $this->forceRatio;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getCacheUrl()
    {
        return $this->cacheUrl;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getError()
    {
        return $this->error;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getAllowance()
    {
        return $this->allowance;
    }
    /**
     * Normalize some titles of known cases.
     *
     * @param string $title
     */
    public static function normalizeTitle($title)
    {
        if (empty($title)) {
            return $title;
        }
        $patterns = [['/\\s+-\\s+Find & Share on GIPHY$/i'], ['']];
        foreach ($patterns[0] as $idx => $pattern) {
            $replace = $patterns[1][$idx];
            if (\preg_match($pattern, $title, $m)) {
                $title = \preg_replace($pattern, $replace, $title);
            }
        }
        return $title;
    }
    /**
     * Normalize some URLs of known cases.
     *
     * @param string $embedUrl
     */
    public static function normalizeEmbedUrl($embedUrl)
    {
        $patterns = [[
            '/.*\\/\\/i\\.ytimg\\.com\\/vi\\/(\\w+)\\/.*/m',
            '/.*?([\\w_-]+)\\.podigee\\.io\\/([\\w_-]+)\\/embed.*/m',
            // Podigee episode
            '/.*?([\\w_-]+)\\.podigee\\.io\\/embed.*/m',
            // Podigee topic without episode
            '/.*open\\.spotify\\.com\\/embed\\/(track|album|playlist|show|episode)\\/([^\\/\\?]+).*/m',
            '/.*(?:reddit|redditmedia)\\.com\\/r\\/([^\\/]+)\\/comments\\/([^\\/]+).*/i',
            '/.*assets\\.pinterest\\.com\\/ext\\/embed\\.html\\?id=(\\d+).*/i',
            '/.*embed\\.music\\.apple\\.com\\/([^\\/]+\\/)?(album|playlist)\\/([^\\/]+)\\/([^\\/\\?]+).*/i',
            '/.*\\/\\/giphy\\.com\\/gifs\\/[\\w-]+-(\\w+)$/m',
            '/.*http(?:s)?(?:%3A|:)(?:\\/|%2F){2}api\\.soundcloud\\.com(?:\\/|%2F)(playlists|tracks)(?:\\/|%2F)(\\d+)(?:(?:%3F|\\?)secret_token(?:%3D|=)(s-\\w+))?.*/m',
            // SoundCloud player embeds with secret token (https://regex101.com/r/8qu9MI/2)
            '/.*\\/\\/(\\w+)\\.podcaster\\.de\\/([^\\/]+)\\/media\\/.*/m',
            // podcaster.de (https://regex101.com/r/TrHcA6/1)
            '/.*youtube-nocookie\\.com\\/embed\\/([\\w-]+).*/m',
            // YouTube nocookie Embed (https://regex101.com/r/3ao0Us/2)
            '/.*player\\.vimeo\\.com\\/video\\/(\\d+).*?.*h=(\\w+).*/m',
            // Vimeo private links (https://regex101.com/r/NxzZv0/1)
            '/.*videopress\\.com\\/embed\\/(\\w+).*/m',
        ], ['https://www.youtube.com/watch?v=$1', 'https://$1.podigee.io/$2', 'https://$1.podigee.io/', 'https://open.spotify.com/$1/$2', 'https://www.reddit.com/r/$1/comments/$2/', 'https://pinterest.com/pin/$1', 'https://music.apple.com/$2/$3/$4', 'https://giphy.com/embed/$1', 'https://api.soundcloud.com/$1/$2?secret_token=$3', 'https://$1.podcaster.de/$2.rss', 'https://www.youtube.com/watch?v=$1', 'https://vimeo.com/$1/$2', 'https://videopress.com/v/$1']];
        foreach ($patterns[0] as $idx => $pattern) {
            $replace = $patterns[1][$idx];
            if (\preg_match($pattern, $embedUrl, $m)) {
                $embedUrl = \preg_replace($pattern, $replace, $embedUrl);
            }
        }
        return $embedUrl;
    }
}
