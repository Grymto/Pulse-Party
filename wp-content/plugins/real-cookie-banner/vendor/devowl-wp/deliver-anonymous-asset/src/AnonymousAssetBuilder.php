<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset;

use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Activator;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils as UtilsUtils;
use WP_Filesystem_Direct;
/**
 * Use this to create your database tables and to create the instances
 * of `DeliverAnonymousAsset`.
 * @internal
 */
class AnonymousAssetBuilder
{
    const TABLE_NAME = 'asset_seo_redirect';
    const OPTION_NAME_SERVE_HASH_SUFFIX = '-serve-hash';
    const OPTION_NAME_SERVE_NEXT_HASH_SUFFIX = '-serve-next-hash';
    const GENERATE_NEXT_HASH = 60 * 60 * 24 * 7;
    const MAX_SEO_REDIRECTS = 4;
    const COPY_EXTENSIONS = ['js', 'css'];
    private $table_name;
    private $optionNamePrefix;
    private $folder;
    /**
     * Cache of `Utils::contentDir`.
     *
     * @var string|false `false` when folder is not writable
     */
    private $contentDir;
    /**
     * The pool of collected built `DeliverAnonymousAsset` instances.
     *
     * @var DeliverAnonymousAsset[]
     */
    private $pool = [];
    /**
     * C'tor.
     *
     * @param string $table_name You can use it in conjunction with `TABLE_NAME` constant
     * @param string $optionNamePrefix
     * @param string $folder The absolute path to your original files
     */
    public function __construct($table_name, $optionNamePrefix, $folder)
    {
        $this->table_name = $table_name;
        $this->optionNamePrefix = $optionNamePrefix;
        $this->folder = $folder;
    }
    /**
     * Get the URL to the anonymous folder.
     */
    public function generateFolderSrc()
    {
        $anonymousFolder = $this->ensureAnonymousFolder(\true);
        $contentDir = \wp_normalize_path(\constant('WP_CONTENT_DIR') . '/');
        $contentUrl = Utils::getContentUrl();
        return \trailingslashit($contentUrl . \substr($anonymousFolder, \strlen($contentDir)));
    }
    /**
     * Create an anonymous asset. Do not forget to make it `->ready()` after you enqueued it!
     * This must be done in `wp` hook as it is the first available hook.
     *
     * @param string $handle
     * @param string $file
     * @param string $id If you pass an ID, the instance will be hold in this class pool and you can use `this::ready()`
     */
    public function build($handle, $file, $id = null)
    {
        $instance = new DeliverAnonymousAsset($this, $handle, $file);
        if ($id !== null) {
            $this->pool[$id] = $instance;
        }
        return $instance;
    }
    /**
     * Make a handle ready. Do not forget to `->build()` it previously!
     *
     * @param string $id
     * @param boolean $condition
     */
    public function ready($id, $condition = \true)
    {
        if (isset($this->pool[$id]) && $condition) {
            $instance = $this->pool[$id];
            return $instance->ready();
        }
        return \false;
    }
    /**
     * Get the currently used hash.
     */
    public function getHash()
    {
        $option = \get_option($this->getOptionNamePrefix() . self::OPTION_NAME_SERVE_HASH_SUFFIX);
        $next = \intval(\get_option($this->getOptionNamePrefix() . self::OPTION_NAME_SERVE_NEXT_HASH_SUFFIX));
        if (empty($option) || $next === 0 || \time() > $next) {
            $option = $this->updateHash();
        }
        return $option;
    }
    /**
     * Create the anonymous folder in `wp-content`. It also uses the original folder path basename (e.g. `dist` or `dev`)
     * as another subfolder, so it results in e.g. `/var/www/html/wp-content/7e9df1a92be399cb922305d5e7388ab1/dist/`.
     *
     * @param boolean|null $skipExistenceCheck
     */
    public function ensureAnonymousFolder($skipExistenceCheck = null)
    {
        $contentDir = $this->getContentDir();
        if (!$contentDir) {
            return \false;
        }
        $hash = $this->getHash();
        $hashFolderPath = $contentDir . $hash . '/';
        $folder = $hashFolderPath . \basename($this->getFolder()) . '/';
        if ($skipExistenceCheck === \true) {
            return $folder;
        }
        // Already exists?
        if ($skipExistenceCheck === null && \is_dir($folder)) {
            return \true;
        }
        if (\wp_mkdir_p($folder)) {
            // Create the anonymous files
            // @codeCoverageIgnoreStart
            if (!\defined('PHPUNIT_FILE')) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            // @codeCoverageIgnoreEnd
            // As we create the folder in `wp-content`, we could have wrong permission for the created
            // anonymous folder due to `wp_mkdir_p()` as it inherits the `chmod` from the parent folder
            UtilsUtils::runDirectFilesystem(function ($fs) use($hashFolderPath, $folder) {
                /**
                 * WP_Filesystem_Direct.
                 *
                 *  @var WP_Filesystem_Direct
                 */
                $fs = $fs;
                $fs->chmod($hashFolderPath, \constant('FS_CHMOD_DIR'));
                $fs->chmod($folder, \constant('FS_CHMOD_DIR'));
            });
            $filesToCopy = \array_filter(\list_files($this->getFolder(), 1), function ($file) {
                $extension = \pathinfo($file, \PATHINFO_EXTENSION);
                return \in_array($extension, self::COPY_EXTENSIONS, \true);
            });
            foreach ($filesToCopy as $fileToCopy) {
                $filename = $folder . self::generateFilename($hash, $fileToCopy);
                \file_put_contents($filename, Utils::readFileAndCorrectSourceMap($fileToCopy));
                UtilsUtils::runDirectFilesystem(function ($fs) use($filename) {
                    /**
                     * WP_Filesystem_Direct.
                     *
                     *  @var WP_Filesystem_Direct
                     */
                    $fs = $fs;
                    $fs->chmod($filename, \constant('FS_CHMOD_FILE'));
                });
            }
            return \true;
        }
        return \false;
    }
    /**
     * Generate a new hash for the current served JS file.
     */
    protected function updateHash()
    {
        global $wpdb;
        $hash = \md5(\wp_generate_uuid4());
        \update_option($this->getOptionNamePrefix() . self::OPTION_NAME_SERVE_HASH_SUFFIX, $hash, \true);
        \update_option($this->getOptionNamePrefix() . self::OPTION_NAME_SERVE_NEXT_HASH_SUFFIX, \time() + self::GENERATE_NEXT_HASH, \true);
        // Save in history
        $table_name = $this->getTableName();
        $wpdb->insert($table_name, ['serve_hash' => $hash, 'created' => \current_time('mysql')]);
        // Read all deleted hashes so `DeliverAnonymousAsset` can delete it
        $sql = "SELECT serve_hash FROM {$table_name} WHERE id NOT IN (SELECT id FROM (SELECT id FROM {$table_name} ORDER BY id DESC LIMIT " . self::MAX_SEO_REDIRECTS . ') foo);';
        // phpcs:disable WordPress.DB.PreparedSQL
        $deletedHashes = $wpdb->get_col($sql);
        // phpcs:enable WordPress.DB.PreparedSQL
        // Only hold x SEO redirects (https://stackoverflow.com/a/578926/5506547)
        $sql = "DELETE FROM {$table_name} WHERE id NOT IN (SELECT id FROM (SELECT id FROM {$table_name} ORDER BY id DESC LIMIT " . self::MAX_SEO_REDIRECTS . ') foo);';
        // phpcs:disable WordPress.DB.PreparedSQL
        $wpdb->query($sql);
        // phpcs:enable WordPress.DB.PreparedSQL
        // Unlink the old folder
        $this->purgeHashes($this->getContentDir(), $deletedHashes);
        /**
         * The JavaScript and CSS files, which were previously anonymous, have been rotated and their hashes have been updated.
         *
         * @see https://devowl.io/knowledge-base/real-cookie-banner-javascript-files-in-wp-content/
         * @hook DevOwl/DeliverAnonymousAsset/Update/$optionNamePrefix
         * @param {string[]} $deletedHashes
         * @param {AnonymousAssetBuilder} $builder
         */
        \do_action('DevOwl/DeliverAnonymousAsset/Update/' . $this->getOptionNamePrefix(), $deletedHashes, $this);
        return $hash;
    }
    /**
     * Make sure the database table is created.
     *
     * @param Activator $activator
     */
    public function dbDelta($activator)
    {
        $charset_collate = $activator->getCharsetCollate();
        $table_name = $this->getTableName();
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            serve_hash char(32) NOT NULL,\n            created datetime NOT NULL,\n            PRIMARY KEY  (id)\n        ) {$charset_collate};";
        \dbDelta($sql);
        // Force to update our assets cause updates can lead to new JavaScript files
        $this->forceRecreation();
    }
    /**
     * Force recreation of asset files.
     */
    public function forceRecreation()
    {
        \update_option($this->getOptionNamePrefix() . self::OPTION_NAME_SERVE_NEXT_HASH_SUFFIX, 0);
    }
    /**
     * Getter.
     */
    public function getTableName()
    {
        global $wpdb;
        return empty($this->table_name) ? $wpdb->prefix . self::TABLE_NAME : $this->table_name;
    }
    /**
     * Getter.
     */
    public function getFolder()
    {
        return $this->folder;
    }
    /**
     * Getter.
     */
    public function getContentDir()
    {
        if ($this->contentDir === null) {
            $this->contentDir = Utils::getContentDir();
        }
        return $this->contentDir;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getOptionNamePrefix()
    {
        return $this->optionNamePrefix;
    }
    /**
     * Generate the filename for a given original filename.
     *
     * @param string $hash The hash to use
     * @param string $originalFilenameOrPath
     */
    public static function generateFilename($hash, $originalFilenameOrPath)
    {
        $basename = \basename($originalFilenameOrPath);
        $extension = \pathinfo($basename, \PATHINFO_EXTENSION);
        return UtilsUtils::simpleHash($hash . $basename) . '.' . $extension;
    }
    /**
     * Hashes got rotated and we can delete old folders from the filesystem.
     *
     * @param string $contentDir
     * @param string[] $hashes
     */
    public static function purgeHashes($contentDir, $hashes)
    {
        if ($contentDir !== \false) {
            UtilsUtils::runDirectFilesystem(function ($fs) use($hashes, $contentDir) {
                foreach ($hashes as $deletedHash) {
                    $fs->rmdir($contentDir . $deletedHash, \true);
                }
            });
        }
    }
    /**
     * Remove the files from filesystem. Use this function in your `uninstall.php`.
     *
     * @param string $table_name
     */
    public static function uninstall($table_name)
    {
        global $wpdb;
        $contentDir = Utils::getContentDir();
        if (!$contentDir) {
            return;
        }
        $sql = "SELECT serve_hash FROM {$table_name}";
        // phpcs:disable WordPress.DB.PreparedSQL
        $hashes = $wpdb->get_col($sql);
        // phpcs:enable WordPress.DB.PreparedSQL
        self::purgeHashes($contentDir, $hashes);
    }
}
