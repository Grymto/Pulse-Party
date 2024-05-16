<?php

namespace DevOwl\RealCookieBanner\comp;

use DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\settings\BannerLink as SettingsBannerLink;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\lite\settings\TcfVendorConfiguration;
use DevOwl\RealCookieBanner\scanner\Persist;
use DevOwl\RealCookieBanner\settings\BannerLink;
use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\CountryBypass;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\settings\TCF;
use DevOwl\RealCookieBanner\Stats;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
use DevOwl\RealCookieBanner\UserConsent;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicy;
use DevOwl\RealCookieBanner\view\checklist\PrivacyPolicyMentionUsage;
use DevOwl\RealCookieBanner\view\customize\banner\BasicLayout;
use DevOwl\RealCookieBanner\view\customize\banner\BodyDesign;
use DevOwl\RealCookieBanner\view\customize\banner\Decision;
use DevOwl\RealCookieBanner\view\customize\banner\individual\SaveButton;
use DevOwl\RealCookieBanner\view\customize\banner\Texts;
use DevOwl\RealCookieBanner\view\Notices;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Apply database migrations when we update the plugin.
 * @internal
 */
class DatabaseUpgrades
{
    use UtilsProvider;
    /**
     * Installed version.
     *
     * @var string|false
     */
    private $installed;
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    public function __construct()
    {
        $this->installed = '';
    }
    /**
     * A new version got installed, run all our migrations if not yet migrated.
     *
     * @param string|false $installed
     */
    public function apply($installed)
    {
        $this->installed = $installed;
        $result = [];
        $result[] = $this->migration_we5cq1();
        $result[] = $this->migration_1td2xu0();
        $result[] = $this->migration_1xwnv8m();
        $result[] = $this->migration_1ydq6ff();
        $result[] = $this->migration_20chay0();
        $result[] = $this->migration_2rb441c();
        $result[] = $this->migration_2d8dedh();
        $result[] = $this->migration_2z4e99b();
        $result[] = $this->migration_2unhn5x();
        $result[] = $this->migration_2undj42();
        $result[] = $this->migration_2vqpmwj();
        $result[] = $this->migration_861m47jgm();
        $result[] = $this->migration_861n6fudh();
        $result[] = $this->migration_863h7nj72();
        $result[] = $this->migration_cawgkp();
        $result[] = $this->migration_866ay8jeb();
        $result[] = $this->migration_866awy2fr();
        $result[] = $this->migration_863gt04va();
        $result[] = $this->migration_apv5uu();
        $result[] = $this->migration_86940n0a0();
        //error_log('---> ' . json_encode($result));
    }
    /**
     * Delete the already known, randomly selected powered-by text and regenerate it.
     *
     * @see https://app.clickup.com/t/we5cq1
     */
    protected function migration_we5cq1()
    {
        if ($this->installed && \version_compare($this->installed, '2.6.5', '<=')) {
            \delete_option(Texts::SETTING_POWERED_BY_TEXT);
            return \true;
        }
        return \false;
    }
    /**
     * We have updated our Real Cookie Banner template and we need to automatically apply the patch to the
     * Real Cookie Banner service.
     *
     * @see https://app.clickup.com/t/1td2xu0
     */
    protected function migration_1td2xu0()
    {
        if ($this->installed && \version_compare($this->installed, '2.11.0', '<=')) {
            // Lazy it, to be compatible with other plugins like WPML or PolyLang...
            \add_action('init', function () {
                $realCookieBannerService = Cookie::getInstance()->getServiceByIdentifier('real-cookie-banner');
                if ($realCookieBannerService !== null) {
                    $template = TemplateConsumers::getCurrentServiceConsumer()->retrieveBy('identifier', 'real-cookie-banner', \true);
                    if (\count($template) > 0) {
                        TemplateConsumers::getInstance()->createFromTemplate($template[0], null, $realCookieBannerService->ID);
                    }
                }
            }, 20);
            return \true;
        }
        return \false;
    }
    /**
     * Reset "Animation only on mobile devices" default to `false` as it should not be activated
     * automatically for already existing users.
     *
     * @see https://app.clickup.com/t/1xwnv8m
     */
    protected function migration_1xwnv8m()
    {
        if (Core::versionCompareOlderThan($this->installed, '2.15.0', ['2.16.0', '2.15.1'])) {
            \update_option(BasicLayout::SETTING_ANIMATION_IN_ONLY_MOBILE, \false);
            \update_option(BasicLayout::SETTING_ANIMATION_OUT_ONLY_MOBILE, \false);
            return \true;
        }
        return \false;
    }
    /**
     * Move all found markups to the respective new database table and drop the known markup column.
     *
     * @see https://app.clickup.com/t/1ydq6ff
     */
    protected function migration_1ydq6ff()
    {
        global $wpdb;
        $table_name = Core::getInstance()->getTableName(Persist::TABLE_NAME);
        $table_name_markup = Core::getInstance()->getTableName(Persist::TABLE_NAME_MARKUP);
        if (Core::versionCompareOlderThan($this->installed, '2.15.0', ['2.16.0', '2.15.1'], function () use($wpdb, $table_name_markup) {
            // phpcs:disable WordPress.DB.PreparedSQL
            $exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name_markup}'") === $table_name_markup;
            // phpcs:enable WordPress.DB.PreparedSQL
            return 0 !== $exists;
        })) {
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query("INSERT IGNORE INTO {$table_name_markup} (markup, markup_hash) SELECT markup, markup_hash FROM {$table_name} WHERE markup IS NOT NULL");
            // phpcs:enable WordPress.DB.PreparedSQL
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query("ALTER TABLE {$table_name} DROP COLUMN markup");
            // phpcs:enable WordPress.DB.PreparedSQL
            return \true;
        }
        return \false;
    }
    /**
     * Deactivate "Use same stylings as 'Accept all'" as it should not be activated automatically for already existing users.
     *
     * @see https://app.clickup.com/t/20chay0
     */
    protected function migration_20chay0()
    {
        if (Core::versionCompareOlderThan($this->installed, '2.17.3', ['2.17.4', '2.18.0'])) {
            \update_option(Decision::SETTING_SHOW_GROUPS, '1');
            \update_option(BodyDesign::SETTING_BUTTON_ACCEPT_ESSENTIALS_USE_ACCEPT_ALL, '');
            \update_option(SaveButton::SETTING_USE_ACCEPT_ALL, '');
            \update_option(Consent::SETTING_LIST_SERVICES_NOTICE, '');
            return \true;
        }
        return \false;
    }
    /**
     * Revert to cookie version 1 for users already using RCB.
     *
     * @see https://app.clickup.com/t/2rb441c
     */
    protected function migration_2rb441c()
    {
        if (Core::versionCompareOlderThan($this->installed, '3.0.1', ['3.0.2', '3.1.0'])) {
            \update_option(Consent::SETTING_COOKIE_VERSION, Consent::COOKIE_VERSION_1);
            return \true;
        }
        return \false;
    }
    /**
     * Multiple metadata rename migrations.
     *
     * @see https://app.clickup.com/t/2d8dedh
     */
    protected function migration_2d8dedh()
    {
        global $wpdb;
        if (Core::versionCompareOlderThan($this->installed, '3.0.2', ['3.0.3', '3.1.0'])) {
            // Get posts which hold post meta which needs to be renamed so we can clear the post cache for them
            $setCookiesViaManager = General::getInstance()->getSetCookiesViaManager();
            $affectedPostIds = $wpdb->get_col(
                // phpcs:disable WordPress.DB.PreparedSQL
                $wpdb->prepare("SELECT p.ID FROM {$wpdb->postmeta} pm\n                    INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID\n                    WHERE pm.meta_key IN ('" . \join("','", \array_merge(['providerPrivacyPolicy', 'codeOptOutDelete', 'noTechnicalDefinitions', 'technicalDefinitions'], ($setCookiesViaManager === 'none' ? [] : $setCookiesViaManager === 'googleTagManager') ? ['googleTagManagerInEventName', 'googleTagManagerOutEventName', 'codeOptInNoGoogleTagManager', 'codeOptOutNoGoogleTagManager'] : ['matomoTagManagerInEventName', 'matomoTagManagerOutEventName', 'codeOptInNoMatomoTagManager', 'codeOptOutNoMatomoTagManager'])) . "') AND p.post_type = %s\n                    GROUP BY p.ID", Cookie::CPT_NAME)
            );
            if (\count($affectedPostIds) > 0) {
                // Rename the metadata directly through a plain SQL query so hooks like `update_post_meta` are not called
                // This avoids issues with WPML or PolyLang and their syncing process
                $wpdb->query(
                    // phpcs:disable WordPress.DB.PreparedSQL
                    $wpdb->prepare("UPDATE {$wpdb->postmeta} pm\n                        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID\n                        SET pm.meta_key = CASE\n                            WHEN pm.meta_key = 'providerPrivacyPolicy' THEN 'providerPrivacyPolicyUrl'\n                            WHEN pm.meta_key = 'codeOptOutDelete' THEN 'deleteTechnicalDefinitionsAfterOptOut'\n                            WHEN pm.meta_key = 'noTechnicalDefinitions' THEN 'isEmbeddingOnlyExternalResources'\n                            " . \join(' ', $setCookiesViaManager === 'googleTagManager' ? ["WHEN pm.meta_key = 'googleTagManagerInEventName' THEN 'tagManagerOptInEventName'", "WHEN pm.meta_key = 'googleTagManagerOutEventName' THEN 'tagManagerOptOutEventName'", "WHEN pm.meta_key = 'codeOptInNoGoogleTagManager' THEN 'executeCodeOptInWhenNoTagManagerConsentIsGiven'", "WHEN pm.meta_key = 'codeOptOutNoGoogleTagManager' THEN 'executeCodeOptOutWhenNoTagManagerConsentIsGiven'"] : ["WHEN pm.meta_key = 'matomoTagManagerInEventName' THEN 'tagManagerOptInEventName'", "WHEN pm.meta_key = 'matomoTagManagerOutEventName' THEN 'tagManagerOptOutEventName'", "WHEN pm.meta_key = 'codeOptInNoMatomoTagManager' THEN 'executeCodeOptInWhenNoTagManagerConsentIsGiven'", "WHEN pm.meta_key = 'codeOptOutNoMatomoTagManager' THEN 'executeCodeOptOutWhenNoTagManagerConsentIsGiven'"]) . "\n                            ELSE pm.meta_key\n                            END,\n                        pm.meta_value = CASE\n                            WHEN pm.meta_key = 'technicalDefinitions' THEN REPLACE(`meta_value`, '\"sessionDuration\"', '\"isSessionDuration\"')\n                            ELSE pm.meta_value\n                            END\n                        WHERE p.post_type = %s", Cookie::CPT_NAME)
                );
                foreach ($affectedPostIds as $affectedPostId) {
                    \clean_post_cache(\intval($affectedPostId));
                }
            }
            // Get posts which hold post meta which needs to be renamed so we can clear the post cache for them
            $affectedPostIds = $wpdb->get_col($wpdb->prepare("SELECT p.ID FROM {$wpdb->postmeta} pm\n                    INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID\n                    WHERE pm.meta_key IN (\n                        'criteria', 'visual', 'visualDarkMode', 'forceHidden', 'hosts', 'cookies'\n                    ) AND p.post_type = %s\n                    GROUP BY p.ID", Blocker::CPT_NAME));
            if (\count($affectedPostIds) > 0) {
                // Rename the metadata directly through a plain SQL query so hooks like `update_post_meta` are not called
                // This avoids issues with WPML or PolyLang and their syncing process
                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} pm\n                        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID\n                        SET pm.meta_key = CASE\n                            WHEN pm.meta_key = 'visual' THEN 'isVisual'\n                            WHEN pm.meta_key = 'visualDarkMode' THEN 'isVisualDarkMode'\n                            WHEN pm.meta_key = 'forceHidden' THEN 'shouldForceToShowVisual'\n                            WHEN pm.meta_key = 'hosts' THEN 'rules'\n                            WHEN pm.meta_key = 'cookies' THEN 'services'\n                            ELSE pm.meta_key\n                            END,\n                        pm.meta_value = CASE\n                            WHEN pm.meta_key = 'criteria' AND pm.meta_value = 'cookies' THEN 'services'\n                            ELSE pm.meta_value\n                            END\n                        WHERE p.post_type = %s", Blocker::CPT_NAME));
                foreach ($affectedPostIds as $affectedPostId) {
                    \clean_post_cache(\intval($affectedPostId));
                }
            }
            return \true;
        }
        return \false;
    }
    /**
     * Create direct aggregates in database instead of calculating from consents.
     *
     * @see https://app.clickup.com/t/2z4e99b
     */
    protected function migration_2z4e99b()
    {
        global $wpdb;
        $table_name_stats_buttons_clicked = $this->getTableName(Stats::TABLE_NAME_BUTTONS_CLICKED);
        $table_name_stats_custom_bypass = $this->getTableName(Stats::TABLE_NAME_CUSTOM_BYPASS);
        $table_name_consent = $this->getTableName(UserConsent::TABLE_NAME);
        if (Core::versionCompareOlderThan($this->installed, '3.4.13', ['3.4.14', '3.5.0'], function () use($wpdb, $table_name_stats_buttons_clicked) {
            // phpcs:disable WordPress.DB.PreparedSQL
            $exists = $wpdb->get_var("SHOW TABLES LIKE '{$table_name_stats_buttons_clicked}'") === $table_name_stats_buttons_clicked;
            // phpcs:enable WordPress.DB.PreparedSQL
            if ($exists) {
                // phpcs:disable WordPress.DB.PreparedSQL
                $countRows = \intval($wpdb->get_var("SELECT COUNT(*) AS cnt FROM {$table_name_stats_buttons_clicked}"));
                // phpcs:enable WordPress.DB.PreparedSQL
                // Only execute the migration once
                return $countRows === 0;
            }
            return \false;
        })) {
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query("INSERT IGNORE INTO {$table_name_stats_buttons_clicked} (context, `day`, button_clicked, `count`)\n                SELECT context, CONVERT(created, DATE) AS `day`, button_clicked, COUNT(DISTINCT uuid) AS cnt\n                    FROM {$table_name_consent}\n                    WHERE forwarded IS NULL\n                    GROUP BY 1, 2, 3");
            $wpdb->query("INSERT IGNORE INTO {$table_name_stats_custom_bypass} (context, `day`, custom_bypass, `count`)\n                SELECT context, CONVERT(created, DATE) AS `day`, IFNULL(custom_bypass, CASE WHEN dnt = 1 THEN 'dnt' ELSE 'none' END) AS custom_bypass, COUNT(DISTINCT uuid) AS cnt\n                    FROM {$table_name_consent}\n                    WHERE forwarded IS NULL\n                    GROUP BY 1, 2, 3");
            // phpcs:enable WordPress.DB.PreparedSQL
            return \true;
        }
        return \false;
    }
    /**
     * Rename `consentForwardingUniqueName` to `uniqueName` and create one if missing.
     *
     * @see https://app.clickup.com/t/2unhn5x
     */
    protected function migration_2unhn5x()
    {
        global $wpdb;
        if (Core::versionCompareOlderThan($this->installed, '3.4.13', ['3.4.14', '3.5.0'])) {
            // Get posts which hold post meta which needs to be renamed so we can clear the post cache for them
            // If `consentForwardingUniqueNameMetaId` is `NULL` you need to insert into that database table
            $result = $wpdb->get_results(
                // phpcs:disable WordPress.DB.PreparedSQL
                $wpdb->prepare("SELECT\n                        p.ID, p.post_name,\n                        pmConsentForwardingUniqueName.meta_id AS consentForwardingUniqueNameMetaId,\n                        pmConsentForwardingUniqueName.meta_value AS consentForwardingUniqueName,\n                        pmUniqueName.meta_value AS uniqueName,\n                        pmTemplateId.meta_value AS presetId,\n                        IFNULL(pmTemplateId.meta_value, p.post_name) AS potentialUniqueName\n                    FROM {$wpdb->posts} p\n                    LEFT JOIN {$wpdb->postmeta} pmConsentForwardingUniqueName\n                        ON pmConsentForwardingUniqueName.post_id = p.ID AND pmConsentForwardingUniqueName.meta_key = 'consentForwardingUniqueName'\n                    LEFT JOIN {$wpdb->postmeta} pmUniqueName\n                        ON pmUniqueName.post_id = p.ID AND pmUniqueName.meta_key = 'uniqueName'\n                    LEFT JOIN {$wpdb->postmeta} pmTemplateId\n                        ON pmTemplateId.post_id = p.ID AND pmTemplateId.meta_key = 'presetId'\n                    WHERE post_type = %s", Cookie::CPT_NAME),
                ARRAY_A
            );
            $affectedPostIds = \array_map(function ($row) {
                return $row['ID'];
            }, $result);
            if (\count($affectedPostIds) > 0) {
                // Rename or create the metadata directly through a plain SQL query so hooks like `update_post_meta` are not called
                // This avoids issues with WPML or PolyLang and their syncing process
                foreach ($result as $row) {
                    if (!empty($row['consentForwardingUniqueNameMetaId']) && !empty($row['consentForwardingUniqueName'])) {
                        // Rename meta_key to `uniqueName`´
                        $wpdb->query(
                            // phpcs:disable WordPress.DB.PreparedSQL
                            $wpdb->prepare("UPDATE {$wpdb->postmeta} SET meta_key = 'uniqueName', meta_value = %s WHERE meta_id = %d", $row['potentialUniqueName'], $row['consentForwardingUniqueNameMetaId']),
                            ARRAY_A
                        );
                    } elseif (empty($row['consentForwardingUniqueNameMetaId']) && empty($row['uniqueName'])) {
                        // Insert new meta `uniqueName`
                        $wpdb->query(
                            // phpcs:disable WordPress.DB.PreparedSQL
                            $wpdb->prepare("INSERT IGNORE INTO {$wpdb->postmeta} (`post_id`, `meta_key`, `meta_value`) VALUES (%d, 'uniqueName', %s)", $row['ID'], $row['potentialUniqueName']),
                            ARRAY_A
                        );
                    }
                }
                foreach ($affectedPostIds as $affectedPostId) {
                    \clean_post_cache(\intval($affectedPostId));
                }
            }
            return \true;
        }
        return \false;
    }
    /**
     * Fill the `ui_view` database column.
     *
     * @see https://app.clickup.com/t/2undj42
     */
    protected function migration_2undj42()
    {
        global $wpdb;
        $table_name = $this->getTableName(UserConsent::TABLE_NAME);
        if (Core::versionCompareOlderThan($this->installed, '3.4.13', ['3.4.14', '3.5.0'])) {
            // phpcs:disable WordPress.DB
            $wpdb->query("UPDATE {$table_name} SET ui_view = 'initial' WHERE dnt = 0 AND custom_bypass IS NULL");
            // phpcs:enable WordPress.DB
            return \true;
        }
        return \false;
    }
    /**
     * Automatically check the checklist item if "Real Cookie Banner" is already mentioned in current
     * privacy policy.
     *
     * @see https://app.clickup.com/t/2vqpmwj
     */
    protected function migration_2vqpmwj()
    {
        if (Core::versionCompareOlderThan($this->installed, '3.4.13', ['3.4.14', '3.5.0'])) {
            PrivacyPolicyMentionUsage::recalculate(BannerLink::getInstance()->getLegalLink(SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY, 'id'));
            return \true;
        }
        return \false;
    }
    /**
     * Automatically convert ePrivacy USA flag to data processing in unsafe countries.
     *
     * @see https://app.clickup.com/t/861m47jgm
     */
    protected function migration_861m47jgm()
    {
        global $wpdb;
        if (Core::versionCompareOlderThan($this->installed, '3.7.2', ['3.7.3', '3.8.0'])) {
            // Enable new feature
            $legacyOptionName = RCB_OPT_PREFIX . '-eprivacy-usa';
            $option = \get_option($legacyOptionName);
            if ($option) {
                \update_option(Consent::SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES, \true);
            }
            //delete_option($legacyOptionName);
            // Get posts which hold post meta which needs to be renamed so we can clear the post cache for them
            $affectedPostIds = $wpdb->get_col($wpdb->prepare("SELECT p.ID FROM {$wpdb->postmeta} pm\n                    INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID\n                    WHERE pm.meta_key IN (\n                        'ePrivacyUSA'\n                    ) AND p.post_type IN (%s, %s)\n                    GROUP BY p.ID", Cookie::CPT_NAME, 'rcb-tcf-vendor-conf'));
            if (\count($affectedPostIds) > 0) {
                // Rename the metadata directly through a plain SQL query so hooks like `update_post_meta` are not called
                // This avoids issues with WPML or PolyLang and their syncing process
                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->postmeta} pm\n                        INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID\n                        SET pm.meta_key = CASE\n                            WHEN pm.meta_key = 'ePrivacyUSA' THEN 'dataProcessingInCountries'\n                            ELSE pm.meta_key\n                            END,\n                        pm.meta_value = CASE\n                            WHEN pm.meta_key = 'ePrivacyUSA' AND pm.meta_value = '1' THEN '[\"US\"]'\n                            WHEN pm.meta_key = 'ePrivacyUSA' AND pm.meta_value <> '1' THEN '[]'\n                            ELSE pm.meta_value\n                            END\n                        WHERE p.post_type IN (%s, %s)", Cookie::CPT_NAME, 'rcb-tcf-vendor-conf'));
                foreach ($affectedPostIds as $affectedPostId) {
                    \clean_post_cache(\intval($affectedPostId));
                }
            }
            return \true;
        }
        return \false;
    }
    /**
     * Update the MD5 hash of truncated JSON strings in the revision database tables.
     *
     * @see https://app.clickup.com/t/861n6fudh
     */
    public function migration_861n6fudh()
    {
        global $wpdb;
        $table_name = $this->getTableName(Revision::TABLE_NAME);
        $table_name_independent = $this->getTableName(Revision::TABLE_NAME_INDEPENDENT);
        if (Core::versionCompareOlderThan($this->installed, '3.9.5', ['3.9.6', '3.10.0'])) {
            // phpcs:disable WordPress.DB
            $wpdb->query("UPDATE {$table_name} SET `hash` = MD5(json_revision)");
            $wpdb->query("UPDATE {$table_name_independent} SET `hash` = MD5(json_revision)");
            // phpcs:enable WordPress.DB
            return \true;
        }
        return \false;
    }
    /**
     * With the introduction of the legal basis in Settings > General, we now need to output the
     * selected legal basis in the cookie banner for the "Data processing in unsafe countries".
     * For this, we update the configured texts and replace the legal basis with the new `{{legalBasis}}` variable.
     *
     * - Add Switzerland (`CH`) to the country bypass list.
     * - Set the TCF publisher country as operator country when given from older installations, and, calculate the `isProviderCurrentWebsite` for all services.
     *
     * @see https://app.clickup.com/t/863h7nj72
     */
    protected function migration_863h7nj72()
    {
        global $wpdb;
        if (Core::versionCompareOlderThan($this->installed, '3.11.5', ['3.12.0', '3.11.6'])) {
            // phpcs:disable WordPress.DB
            $sql = \sprintf("UPDATE {$wpdb->options}\n                SET option_value = REPLACE(REPLACE(option_value, 'Art. 49 Abs. 1 lit. a DSGVO', '{{legalBasis}}'), 'Art. 49 (1) lit. a GDPR', '{{legalBasis}}')\n                WHERE `option_name` LIKE '%s%%'", Texts::SETTING_DATA_PROCESSING_IN_UNSAFE_COUNTRIES);
            $count = $wpdb->query($sql);
            // phpcs:enable WordPress.DB
            if ($count > 0) {
                \wp_cache_delete('alloptions', 'options');
            }
            // Add Switzerland (`CH`) to the country bypass list.
            // We do not use `getCountries()` as we need the non-expanded country list
            $countries = \get_option(CountryBypass::SETTING_COUNTRY_BYPASS_COUNTRIES);
            if (!empty($countries)) {
                $countries = \explode(',', $countries);
                if (!\in_array('CH', $countries, \true)) {
                    $countries[] = 'CH';
                    \update_option(CountryBypass::SETTING_COUNTRY_BYPASS_COUNTRIES, \join(',', $countries));
                }
            }
            $deleteCountryForExistingUsers = \true;
            if ($this->isPro()) {
                $tcfPublisherCc = \get_option(TCF::SETTING_TCF_PUBLISHER_CC);
                if (!empty($tcfPublisherCc)) {
                    // We need to call this after `enableOptionAutoload`
                    \add_action('init', function () use($tcfPublisherCc) {
                        \update_option(General::SETTING_OPERATOR_COUNTRY, $tcfPublisherCc);
                    }, 11);
                    \delete_option(TCF::SETTING_TCF_PUBLISHER_CC);
                    $deleteCountryForExistingUsers = \false;
                }
            }
            if ($deleteCountryForExistingUsers) {
                // We need to call this after `enableOptionAutoload`
                \add_action('init', function () {
                    \update_option(General::SETTING_OPERATOR_COUNTRY, '');
                }, 11);
            }
            // Get posts which hold post meta which needs to be renamed so we can clear the post cache for them
            $affectedPostIds = $wpdb->get_col($wpdb->prepare("SELECT DISTINCT(p.ID)\n                    FROM {$wpdb->posts} p\n                    LEFT JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id AND pm.meta_key = 'isProviderCurrentWebsite'\n                    WHERE p.post_type = %s AND pm.meta_value IS NULL", Cookie::CPT_NAME));
            if (\count($affectedPostIds) > 0) {
                // Insert the metadata directly through a plain SQL query so hooks like `update_post_meta` are not called
                // This avoids issues with WPML or PolyLang and their syncing process
                $wpdb->query($wpdb->prepare(
                    "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) SELECT p.ID, 'isProviderCurrentWebsite',\n                            CASE\n                                WHEN pmPrivacy.meta_value LIKE %s THEN '1'\n                                WHEN pmLegal.meta_value LIKE %s THEN '1'\n                                WHEN pmProvider.meta_value LIKE %s THEN '1'\n                                WHEN pmProvider.meta_value LIKE %s THEN '1'\n                                ELSE ''\n                            END AS isProviderCurrentWebsite\n                        FROM {$wpdb->posts} p\n                        LEFT JOIN {$wpdb->postmeta} pmProvider\n                            ON p.ID = pmProvider.post_id AND pmProvider.meta_key = 'provider'\n                        LEFT JOIN {$wpdb->postmeta} pmPrivacy\n                            ON p.ID = pmPrivacy.post_id AND pmPrivacy.meta_key = 'providerPrivacyPolicyUrl'\n                        LEFT JOIN {$wpdb->postmeta} pmLegal\n                            ON p.ID = pmLegal.post_id AND pmLegal.meta_key = 'providerLegalNoticeUrl'\n                        WHERE p.post_type = %s\n                        AND NOT EXISTS (\n                            SELECT 1 FROM {$wpdb->postmeta} pm\n                            WHERE pm.post_id = p.ID\n                            AND pm.meta_key = 'isProviderCurrentWebsite'\n                        )",
                    '%' . Utils::host(Utils::HOST_TYPE_MAIN) . '%',
                    '%' . Utils::host(Utils::HOST_TYPE_MAIN) . '%',
                    \get_bloginfo('name'),
                    // needed for backwards-compatibility due to a bug in older Real Cookie Banner versions
                    \html_entity_decode(\get_bloginfo('name')),
                    Cookie::CPT_NAME
                ));
                foreach ($affectedPostIds as $affectedPostId) {
                    \clean_post_cache(\intval($affectedPostId));
                }
            }
            return \true;
        }
        return \false;
    }
    /**
     * With the introduction of the footer links in Cookies > Settings, we also removed the "Legal" section
     * in the customizer. The links are now completely customizable via a custom post type.
     *
     * @see https://app.clickup.com/t/cawgkp
     */
    protected function migration_cawgkp()
    {
        if (Core::versionCompareOlderThan($this->installed, '3.11.5', ['3.12.0', '3.11.6'])) {
            BannerLink::getInstance()->createDefaults(\true);
            return \true;
        }
        return \false;
    }
    /**
     * With the introduction of the footer links in Cookies > Settings, we had a bug that migrated banner links
     * did not update the checklist item accordingly.
     *
     * @see https://app.clickup.com/t/866ay8jeb
     */
    protected function migration_866ay8jeb()
    {
        if (Core::versionCompareOlderThan($this->installed, '3.12.0', ['3.13.0', '3.12.1'])) {
            \add_action('init', function () {
                $privacyPolicyId = BannerLink::getInstance()->getLegalLink(SettingsBannerLink::PAGE_TYPE_PRIVACY_POLICY, 'id');
                if ($privacyPolicyId > 0) {
                    if (!Checklist::getInstance()->isChecked(PrivacyPolicy::IDENTIFIER)) {
                        PrivacyPolicy::recalculate($privacyPolicyId);
                    }
                    if (!Checklist::getInstance()->isChecked(PrivacyPolicyMentionUsage::IDENTIFIER)) {
                        PrivacyPolicyMentionUsage::recalculate($privacyPolicyId);
                    }
                }
            }, 8);
            return \true;
        }
        return \false;
    }
    /**
     * With the introduction of the age notice age limit in Settings > Consent, we now need to output the
     * age limit in the cookie banner. For this, we update the configured texts and replace the "16 years"
     * with the new `{{minAge}} years` variable.
     *
     * @see https://app.clickup.com/t/866awy2fr
     */
    protected function migration_866awy2fr()
    {
        global $wpdb;
        if (Core::versionCompareOlderThan($this->installed, '3.12.0', ['3.13.0', '3.12.1'])) {
            // phpcs:disable WordPress.DB
            $sql = \sprintf("UPDATE {$wpdb->options}\n                SET option_value = REPLACE(REPLACE(option_value, '16 years', '{{minAge}} years'), '16 Jahre', '{{minAge}} Jahre')\n                WHERE `option_name` LIKE '%s%%' OR `option_name` LIKE '%s%%'", Texts::SETTING_AGE_NOTICE, Texts::SETTING_AGE_NOTICE_BLOCKER);
            $count = $wpdb->query($sql);
            // phpcs:enable WordPress.DB
            if ($count > 0) {
                \wp_cache_delete('alloptions', 'options');
            }
            return \true;
        }
        return \false;
    }
    /**
     * With the introduction of TCF 2.2, IAB Europe recommends to only use TCF vendors which are really used
     * while data processing. When there are more than x vendors created previsouly, show a notice.
     *
     * @see https://app.clickup.com/t/863gt04va
     */
    protected function migration_863gt04va()
    {
        if (Core::versionCompareOlderThan($this->installed, '4.0.0', ['4.1.0', '4.0.1']) && $this->isPro()) {
            \add_action('init', function () {
                if (TCF::getInstance()->isActive()) {
                    $count = TcfVendorConfiguration::getInstance()->getAllCount();
                    if ($count > Notices::TCF_TOO_MUCH_VENDORS) {
                        Core::getInstance()->getNotices()->getStates()->set(Notices::NOTICE_TCF_TOO_MUCH_VENDORS, \true);
                    }
                }
            }, 8);
            return \true;
        }
        return \false;
    }
    /**
     * We have renamed the TCF Stacks in customizer to "Accordion" so it is not restricted to TCF-only.
     *
     * @see https://app.clickup.com/t/apv5uu
     * @see https://app.clickup.com/2088/v/dc/218-357/218-12212?block=block-fc8ad147-87f1-4c73-bdb1-2f8faa1dd462
     */
    protected function migration_apv5uu()
    {
        global $wpdb;
        if (Core::versionCompareOlderThan($this->installed, '4.3.7', ['4.3.8', '4.4.0']) || Core::versionCompareOlderThan($this->installed, '4.4.0', ['4.4.1', '4.5.0'])) {
            // phpcs:disable WordPress.DB.PreparedSQL
            $wpdb->query("INSERT INTO {$wpdb->options} (`option_name`, `option_value`, `autoload`)\n                SELECT\n                    REPLACE(option_name, 'tcf-stacks', 'accordion') AS option_name,\n                    option_value,\n                    'yes' as autoload\n                FROM {$wpdb->options}\n                WHERE option_name LIKE 'rcb-banner-body-design-tcf-stacks%'\n                ON DUPLICATE KEY UPDATE option_value = VALUES(option_value)");
            // phpcs:enable WordPress.DB.PreparedSQL
            \wp_cache_delete('alloptions', 'options');
            return \true;
        }
        return \false;
    }
    /**
     * Disable maximum height customize setting for the cookie banner for already existing users.
     *
     * @see https://app.clickup.com/t/86940n0a0
     */
    protected function migration_86940n0a0()
    {
        if (Core::versionCompareOlderThan($this->installed, '4.5.4', ['4.5.5', '4.6.0'])) {
            \update_option(BasicLayout::SETTING_MAX_HEIGHT_ENABLED, '');
            return \true;
        }
        return \false;
    }
}
