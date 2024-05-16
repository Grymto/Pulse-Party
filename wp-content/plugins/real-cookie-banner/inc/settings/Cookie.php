<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services\Service as ServicesService;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Utils;
use stdClass;
use WP_Error;
use WP_Post;
use WP_REST_Posts_Controller;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Register cookie custom post type.
 * @internal
 */
class Cookie
{
    use UtilsProvider;
    const CPT_NAME = 'rcb-cookie';
    const META_NAME_IS_PROVIDER_CURRENT_WEBSITE = 'isProviderCurrentWebsite';
    const META_NAME_PROVIDER = 'provider';
    const META_NAME_PROVIDER_CONTACT_PHONE = 'providerContactPhone';
    const META_NAME_PROVIDER_CONTACT_EMAIL = 'providerContactEmail';
    const META_NAME_PROVIDER_CONTACT_LINK = 'providerContactLink';
    const META_NAME_UNIQUE_NAME = 'uniqueName';
    const META_NAME_IS_EMBEDDING_ONLY_EXTERNAL_RESOURCES = 'isEmbeddingOnlyExternalResources';
    const META_NAME_LEGAL_BASIS = 'legalBasis';
    const META_NAME_DATA_PROCESSING_IN_COUNTRIES = 'dataProcessingInCountries';
    const META_NAME_DATA_PROCESSING_IN_COUNTRIES_SPECIAL_TREATMENTS = 'dataProcessingInCountriesSpecialTreatments';
    const META_NAME_TECHNICAL_DEFINITIONS = 'technicalDefinitions';
    const META_NAME_CODE_DYNAMICS = 'codeDynamics';
    const META_NAME_PROVIDER_PRIVACY_POLICY_URL = 'providerPrivacyPolicyUrl';
    const META_NAME_PROVIDER_LEGAL_NOTICE_URL = 'providerLegalNoticeUrl';
    const META_NAME_GOOGLE_CONSENT_MODE_CONSENT_TYPES = 'googleConsentModeConsentTypes';
    const META_NAME_TAG_MANAGER_OPT_IN_EVENT_NAME = 'tagManagerOptInEventName';
    const META_NAME_TAG_MANAGER_OPT_OUT_EVENT_NAME = 'tagManagerOptOutEventName';
    const META_NAME_CODE_OPT_IN = 'codeOptIn';
    const META_NAME_EXECUTE_CODE_OPT_IN_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN = 'executeCodeOptInWhenNoTagManagerConsentIsGiven';
    const META_NAME_CODE_OPT_OUT = 'codeOptOut';
    const META_NAME_EXECUTE_CODE_OPT_OUT_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN = 'executeCodeOptOutWhenNoTagManagerConsentIsGiven';
    const META_NAME_DELETE_TECHNICAL_DEFINITIONS_AFTER_OPT_OUT = 'deleteTechnicalDefinitionsAfterOptOut';
    const META_NAME_CODE_ON_PAGE_LOAD = 'codeOnPageLoad';
    const SYNC_META_COPY = [\DevOwl\RealCookieBanner\settings\Cookie::META_NAME_UNIQUE_NAME, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_IS_PROVIDER_CURRENT_WEBSITE, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_IS_EMBEDDING_ONLY_EXTERNAL_RESOURCES, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_LEGAL_BASIS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_DATA_PROCESSING_IN_COUNTRIES, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_DATA_PROCESSING_IN_COUNTRIES_SPECIAL_TREATMENTS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TECHNICAL_DEFINITIONS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_DYNAMICS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TAG_MANAGER_OPT_IN_EVENT_NAME, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TAG_MANAGER_OPT_OUT_EVENT_NAME, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_GOOGLE_CONSENT_MODE_CONSENT_TYPES, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_OPT_IN, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_EXECUTE_CODE_OPT_IN_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_OPT_OUT, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_EXECUTE_CODE_OPT_OUT_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_DELETE_TECHNICAL_DEFINITIONS_AFTER_OPT_OUT, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_ON_PAGE_LOAD, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_ID, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_VERSION];
    const SYNC_META_COPY_ONCE = [\DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_CONTACT_PHONE, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_CONTACT_EMAIL, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_CONTACT_LINK, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_PRIVACY_POLICY_URL, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_LEGAL_NOTICE_URL];
    const TECHNICAL_HANDLING_META_COLLECTION = [
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TAG_MANAGER_OPT_IN_EVENT_NAME,
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TAG_MANAGER_OPT_OUT_EVENT_NAME,
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_OPT_IN,
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_EXECUTE_CODE_OPT_IN_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN,
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_OPT_OUT,
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_EXECUTE_CODE_OPT_OUT_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN,
        // Cookie::META_NAME_DELETE_TECHNICAL_DEFINITIONS_AFTER_OPT_OUT,
        \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_ON_PAGE_LOAD,
    ];
    const SYNC_OPTIONS = ['data' => ['menu_order'], 'taxonomies' => [\DevOwl\RealCookieBanner\settings\CookieGroup::TAXONOMY_NAME], 'meta' => ['copy' => \DevOwl\RealCookieBanner\settings\Cookie::SYNC_META_COPY, 'copy-once' => \DevOwl\RealCookieBanner\settings\Cookie::SYNC_META_COPY_ONCE]];
    const META_KEYS = [\DevOwl\RealCookieBanner\settings\Cookie::META_NAME_IS_PROVIDER_CURRENT_WEBSITE, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_CONTACT_PHONE, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_CONTACT_EMAIL, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_CONTACT_LINK, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_UNIQUE_NAME, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_IS_EMBEDDING_ONLY_EXTERNAL_RESOURCES, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_LEGAL_BASIS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_DATA_PROCESSING_IN_COUNTRIES, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_DATA_PROCESSING_IN_COUNTRIES_SPECIAL_TREATMENTS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TECHNICAL_DEFINITIONS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_DYNAMICS, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_PRIVACY_POLICY_URL, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_PROVIDER_LEGAL_NOTICE_URL, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TAG_MANAGER_OPT_IN_EVENT_NAME, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_TAG_MANAGER_OPT_OUT_EVENT_NAME, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_GOOGLE_CONSENT_MODE_CONSENT_TYPES, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_OPT_IN, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_EXECUTE_CODE_OPT_IN_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_OPT_OUT, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_EXECUTE_CODE_OPT_OUT_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_DELETE_TECHNICAL_DEFINITIONS_AFTER_OPT_OUT, \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_CODE_ON_PAGE_LOAD, \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_ID];
    /**
     * This capabilities are added to the custom post type. This allows to use one main capability
     * `manage_real_cookie_banner` instead of granular capabilities like `edit_rcb-cookie`, ...
     *
     * @see https://developer.wordpress.org/reference/functions/register_post_type/#capabilities
     */
    const CAPABILITIES = ['edit_post' => Core::MANAGE_MIN_CAPABILITY, 'read_post' => Core::MANAGE_MIN_CAPABILITY, 'delete_post' => Core::MANAGE_MIN_CAPABILITY, 'edit_posts' => Core::MANAGE_MIN_CAPABILITY, 'edit_others_posts' => Core::MANAGE_MIN_CAPABILITY, 'publish_posts' => Core::MANAGE_MIN_CAPABILITY, 'read_private_posts' => Core::MANAGE_MIN_CAPABILITY, 'delete_posts' => Core::MANAGE_MIN_CAPABILITY, 'delete_private_posts' => Core::MANAGE_MIN_CAPABILITY, 'delete_published_posts' => Core::MANAGE_MIN_CAPABILITY, 'delete_others_posts' => Core::MANAGE_MIN_CAPABILITY, 'edit_private_posts' => Core::MANAGE_MIN_CAPABILITY, 'edit_published_posts' => Core::MANAGE_MIN_CAPABILITY, 'edit_posts' => Core::MANAGE_MIN_CAPABILITY];
    /**
     * Singleton instance.
     *
     * @var Cookie
     */
    private static $me = null;
    private $cacheGetOrdered = [];
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Register custom post type.
     */
    public function register()
    {
        $labels = ['name' => \__('Cookies', RCB_TD), 'singular_name' => \__('Cookie', RCB_TD)];
        $args = ['label' => $labels['name'], 'labels' => $labels, 'description' => '', 'public' => \false, 'publicly_queryable' => \false, 'show_ui' => \true, 'show_in_rest' => \true, 'rest_base' => self::CPT_NAME, 'rest_controller_class' => WP_REST_Posts_Controller::class, 'has_archive' => \false, 'show_in_menu' => \false, 'show_in_nav_menus' => \false, 'delete_with_user' => \false, 'exclude_from_search' => \true, 'capabilities' => self::CAPABILITIES, 'map_meta_cap' => \false, 'hierarchical' => \false, 'rewrite' => \false, 'query_var' => \true, 'supports' => ['title', 'editor', 'custom-fields', 'page-attributes']];
        \register_post_type(self::CPT_NAME, $args);
        \register_meta('post', \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_ID, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_VERSION, ['object_subtype' => self::CPT_NAME, 'type' => 'number', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_IS_PROVIDER_CURRENT_WEBSITE, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PROVIDER, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PROVIDER_CONTACT_PHONE, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PROVIDER_CONTACT_EMAIL, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PROVIDER_CONTACT_LINK, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_UNIQUE_NAME, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_LEGAL_BASIS, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'default' => ServicesService::LEGAL_BASIS_CONSENT, 'show_in_rest' => ['schema' => ['type' => 'string', 'enum' => ServicesService::LEGAL_BASIS]]]);
        \register_meta('post', self::META_NAME_IS_EMBEDDING_ONLY_EXTERNAL_RESOURCES, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_DATA_PROCESSING_IN_COUNTRIES, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_DATA_PROCESSING_IN_COUNTRIES_SPECIAL_TREATMENTS, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_TECHNICAL_DEFINITIONS, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'sanitize_callback' => [Service::class, 'sanitizePostMetaJson'], 'show_in_rest' => \true]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_CODE_DYNAMICS, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PROVIDER_PRIVACY_POLICY_URL, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_PROVIDER_LEGAL_NOTICE_URL, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_CODE_OPT_IN, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_EXECUTE_CODE_OPT_IN_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_CODE_OPT_OUT, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_EXECUTE_CODE_OPT_OUT_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_DELETE_TECHNICAL_DEFINITIONS_AFTER_OPT_OUT, ['object_subtype' => self::CPT_NAME, 'type' => 'boolean', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_CODE_ON_PAGE_LOAD, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_TAG_MANAGER_OPT_IN_EVENT_NAME, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        \register_meta('post', self::META_NAME_TAG_MANAGER_OPT_OUT_EVENT_NAME, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
        // This meta is stored as JSON (this shouldn't be done usually - 3rd normal form - but it's ok here)
        \register_meta('post', self::META_NAME_GOOGLE_CONSENT_MODE_CONSENT_TYPES, ['object_subtype' => self::CPT_NAME, 'type' => 'string', 'single' => \true, 'show_in_rest' => \true]);
    }
    /**
     * Get all available cookies ordered by group. You also get a `metas` property
     * in the returned WP_Post instance with all RCB-relevant metas.
     *
     * @param int $groupId
     * @param boolean $force
     * @param WP_Post[] $usePosts If set, only meta is applied to the passed posts
     * @return WP_Post[]|WP_Error
     */
    public function getOrdered($groupId, $force = \false, $usePosts = null)
    {
        if ($force === \false && isset($this->cacheGetOrdered[$groupId]) && $usePosts === null) {
            return $this->cacheGetOrdered[$groupId];
        }
        $posts = [];
        if ($usePosts) {
            $allPosts = $usePosts;
        } else {
            // Make 'all' cache context-depending to avoid WPML / PolyLang issues (e. g. request new consent)
            $allKey = 'all-' . \DevOwl\RealCookieBanner\settings\Revision::getInstance()->getContextVariablesString();
            if ($force === \false && isset($this->cacheGetOrdered[$allKey])) {
                $allPosts = $this->cacheGetOrdered[$allKey];
            } else {
                $allPosts = $this->cacheGetOrdered[$allKey] = \get_posts(Core::getInstance()->queryArguments(['post_type' => self::CPT_NAME, 'orderby' => ['menu_order' => 'ASC', 'ID' => 'DESC'], 'numberposts' => -1, 'nopaging' => \true, 'post_status' => 'publish'], 'cookiesGetOrdered'));
            }
        }
        // Filter terms to only get services for this requested group
        if ($groupId !== null) {
            foreach ($allPosts as $post) {
                $terms = \get_the_terms($post->ID, \DevOwl\RealCookieBanner\settings\CookieGroup::TAXONOMY_NAME);
                if (\is_array($terms) && \count($terms) > 0 && $terms[0]->term_id === $groupId) {
                    $posts[] = $post;
                }
            }
        } else {
            $posts = $allPosts;
        }
        foreach ($posts as &$post) {
            $post->metas = ['providerContact' => new stdClass()];
            foreach (self::META_KEYS as $meta_key) {
                $metaValue = \get_post_meta($post->ID, $meta_key, \true);
                switch ($meta_key) {
                    case self::META_NAME_TECHNICAL_DEFINITIONS:
                        $metaValue = Utils::isJson($metaValue, []);
                        foreach ($metaValue as $key => $definition) {
                            $metaValue[$key]['duration'] = \intval(isset($definition['duration']) ? $definition['duration'] : 0);
                        }
                        break;
                    case self::META_NAME_CODE_DYNAMICS:
                    case self::META_NAME_DATA_PROCESSING_IN_COUNTRIES:
                    case self::META_NAME_DATA_PROCESSING_IN_COUNTRIES_SPECIAL_TREATMENTS:
                    case self::META_NAME_GOOGLE_CONSENT_MODE_CONSENT_TYPES:
                        $metaValue = Utils::isJson($metaValue, []);
                        break;
                    case self::META_NAME_IS_PROVIDER_CURRENT_WEBSITE:
                    case self::META_NAME_IS_EMBEDDING_ONLY_EXTERNAL_RESOURCES:
                    case self::META_NAME_EXECUTE_CODE_OPT_IN_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN:
                    case self::META_NAME_EXECUTE_CODE_OPT_OUT_WHEN_NO_TAG_MANAGER_CONSENT_IS_GIVEN:
                    case self::META_NAME_DELETE_TECHNICAL_DEFINITIONS_AFTER_OPT_OUT:
                        $metaValue = \boolval($metaValue);
                        break;
                    case \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_VERSION:
                        $metaValue = \intval($metaValue);
                        break;
                    case self::META_NAME_PROVIDER_CONTACT_PHONE:
                    case self::META_NAME_PROVIDER_CONTACT_EMAIL:
                    case self::META_NAME_PROVIDER_CONTACT_LINK:
                        $meta_key = \lcfirst(\substr($meta_key, \strlen('providerContact')));
                        $post->metas['providerContact']->{$meta_key} = $metaValue;
                        $meta_key = null;
                        break;
                    default:
                        break;
                }
                if ($meta_key !== null) {
                    $post->metas[$meta_key] = $metaValue;
                }
            }
        }
        if ($usePosts === null) {
            $this->cacheGetOrdered[$groupId] = $posts;
        }
        return $posts;
    }
    /**
     * Get unassigned services (cookies without cookie group).
     */
    public function getUnassignedCookies()
    {
        return \get_posts(Core::getInstance()->queryArguments(['post_type' => \DevOwl\RealCookieBanner\settings\Cookie::CPT_NAME, 'numberposts' => -1, 'nopaging' => \true, 'post_status' => ['publish', 'private', 'draft'], 'tax_query' => [[
            // https://wordpress.stackexchange.com/a/252102/83335
            'taxonomy' => \DevOwl\RealCookieBanner\settings\CookieGroup::TAXONOMY_NAME,
            'operator' => 'NOT EXISTS',
        ]]], 'cookiesUnassigned'));
    }
    /**
     * Get a total count of published cookies.
     *
     * @return int
     */
    public function getPublicCount()
    {
        return \intval(\wp_count_posts(self::CPT_NAME)->publish);
    }
    /**
     * Get a total count of all cookies.
     *
     * @return int
     */
    public function getAllCount()
    {
        return \array_sum(\array_map('intval', \array_values((array) \wp_count_posts(self::CPT_NAME))));
    }
    /**
     * Get a created service by identifier.
     *
     * @param string $identifier
     */
    public function getServiceByIdentifier($identifier)
    {
        $realCookieBannerService = \get_posts(Core::getInstance()->queryArguments(['post_type' => \DevOwl\RealCookieBanner\settings\Cookie::CPT_NAME, 'numberposts' => -1, 'nopaging' => \true, 'meta_query' => [['key' => \DevOwl\RealCookieBanner\settings\Blocker::META_NAME_PRESET_ID, 'value' => $identifier, 'compare' => '=']], 'post_status' => ['publish', 'private', 'draft']], 'Cookie::getServiceByIdentifier'));
        return $realCookieBannerService[0] ?? null;
    }
    /**
     * Get all available services by an unique name.
     *
     * @param string $slug
     */
    public function getServiceByUniqueName($slug)
    {
        $result = [];
        // unique IDs because we fetch multiple times
        $byMeta = \get_posts(Core::getInstance()->queryArguments(['post_type' => \DevOwl\RealCookieBanner\settings\Cookie::CPT_NAME, 'posts_per_page' => -1, 'meta_query' => [['key' => \DevOwl\RealCookieBanner\settings\Cookie::META_NAME_UNIQUE_NAME, 'value' => $slug, 'compare' => '=']]], 'forwardingGetUniqueNameByMeta'));
        foreach ($byMeta as $row) {
            $result[$row->ID] = $row;
        }
        if (\count($result) === 0) {
            return new WP_Error('not_found', \__('Not found'), ['status' => 404]);
        }
        return \array_values($this->getOrdered(null, \false, $result));
    }
    /**
     * Modify the cookie item schema and allow to pass the opt-in codes as base64-encoded strings
     * so they do not get inspected as XSS e.g. in Cloudflare.
     *
     * @param array $schema
     */
    public function rest_item_schema($schema)
    {
        $schema['properties']['meta']['arg_options']['sanitize_callback'] = function ($properties) {
            $base64Start = 'encodedScript:';
            // 'data:text/plain;base64,'; // Cloudflare XSS can also protect again this encoding
            foreach ([self::META_NAME_CODE_OPT_IN, self::META_NAME_CODE_OPT_OUT, self::META_NAME_CODE_ON_PAGE_LOAD] as $meta_key) {
                if (isset($properties[$meta_key]) && \strpos($properties[$meta_key], $base64Start) === 0) {
                    $base65String = \substr($properties[$meta_key], \strlen($base64Start));
                    $properties[$meta_key] = empty($base65String) ? '' : \base64_decode($base65String);
                }
            }
            return $properties;
        };
        return $schema;
    }
    /**
     * Opposite of `registerPostTypeCapToRoles`.
     *
     * @deprecated Just for backwards compatibility to remove the old capabilities properly
     * @param string $cptName
     */
    public static function deregisterPostTypeCapToRoles($cptName)
    {
        foreach (\wp_roles()->role_objects as $role) {
            if ($role->has_cap(Core::ADD_MANAGE_MIN_CAPABILITY_TO_ALL_USERS_WITH)) {
                foreach ([
                    'edit_%s',
                    'read_%s',
                    'delete_%s',
                    // Primitive capabilities used outside of map_meta_cap():
                    'edit_%ss',
                    'edit_others_%ss',
                    'publish_%ss',
                    'read_private_%ss',
                    // Primitive capabilities used within map_meta_cap():
                    'delete_%ss',
                    'delete_private_%ss',
                    'delete_published_%ss',
                    'delete_others_%ss',
                    'edit_private_%ss',
                    'edit_published_%ss',
                    'edit_%ss',
                ] as $cap) {
                    $removeCap = \sprintf($cap, $cptName);
                    if ($role->has_cap($removeCap)) {
                        $role->remove_cap($removeCap);
                    }
                }
            }
        }
    }
    /**
     * Get singleton instance.
     *
     * @return Cookie
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\Cookie() : self::$me;
    }
}
