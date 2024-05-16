<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Currently this ISO code mapper supports the following:
 *
 * - Convert two-letter code to WordPress compatible code
 * @internal
 */
class IsoCodeMapper
{
    /**
     * Result of `self::printIsoWordPressMapping`
     */
    const TWO_LETTER_TO_WORDPRESS = ['af' => 'af', 'afr' => 'af', 'ar' => 'ar', 'ara' => 'ar', 'ary' => 'ary', 'as' => 'as', 'asm' => 'as', 'az' => 'az', 'aze' => 'az', 'azb' => 'azb', 'be' => 'bel', 'bel' => 'bel', 'bg' => 'bg_BG', 'bul' => 'bg_BG', 'bn' => 'bn_BD', 'bo' => 'bo', 'tib' => 'bo', 'bs' => 'bs_BA', 'bos' => 'bs_BA', 'ca' => 'ca', 'cat' => 'ca', 'ceb' => 'ceb', 'cs' => 'cs_CZ', 'ces' => 'cs_CZ', 'cy' => 'cy', 'cym' => 'cy', 'da' => 'da_DK', 'dan' => 'da_DK', 'de' => 'de_DE', 'dsb' => 'dsb', 'dz' => 'dzo', 'dzo' => 'dzo', 'el' => 'el', 'ell' => 'el', 'en' => 'en_GB', 'eng' => 'en_GB', 'eo' => 'eo', 'epo' => 'eo', 'es' => 'es_ES', 'spa' => 'es_AR', 'et' => 'et', 'est' => 'et', 'eu' => 'eu', 'eus' => 'eu', 'fa' => 'fa_IR', 'fas' => 'fa_IR', 'fi' => 'fi', 'fin' => 'fi', 'fr' => 'fr_FR', 'fra' => 'fr_BE', 'fur' => 'fur', 'gd' => 'gd', 'gla' => 'gd', 'gl' => 'gl_ES', 'glg' => 'gl_ES', 'gu' => 'gu', 'guj' => 'gu', 'haz' => 'haz', 'he' => 'he_IL', 'hi' => 'hi_IN', 'hin' => 'hi_IN', 'hr' => 'hr', 'hrv' => 'hr', 'hsb' => 'hsb', 'hu' => 'hu_HU', 'hun' => 'hu_HU', 'hy' => 'hy', 'hye' => 'hy', 'id' => 'id_ID', 'ind' => 'id_ID', 'is' => 'is_IS', 'isl' => 'is_IS', 'it' => 'it_IT', 'ita' => 'it_IT', 'ja' => 'ja', 'jv' => 'jv_ID', 'jav' => 'jv_ID', 'ka' => 'ka_GE', 'kat' => 'ka_GE', 'kab' => 'kab', 'kk' => 'kk', 'kaz' => 'kk', 'km' => 'km', 'khm' => 'km', 'kn' => 'kn', 'kan' => 'kn', 'ko' => 'ko_KR', 'kor' => 'ko_KR', 'ku' => 'ckb', 'ckb' => 'ckb', 'lo' => 'lo', 'lao' => 'lo', 'lt' => 'lt_LT', 'lit' => 'lt_LT', 'lv' => 'lv', 'lav' => 'lv', 'mk' => 'mk_MK', 'mkd' => 'mk_MK', 'ml' => 'ml_IN', 'mal' => 'ml_IN', 'mn' => 'mn', 'mon' => 'mn', 'mr' => 'mr', 'mar' => 'mr', 'ms' => 'ms_MY', 'msa' => 'ms_MY', 'my' => 'my_MM', 'mya' => 'my_MM', 'nb' => 'nb_NO', 'nob' => 'nb_NO', 'ne' => 'ne_NP', 'nep' => 'ne_NP', 'nl' => 'nl_NL', 'nld' => 'nl_NL', 'nn' => 'nn_NO', 'nno' => 'nn_NO', 'oc' => 'oci', 'oci' => 'oci', 'pa' => 'pa_IN', 'pan' => 'pa_IN', 'pl' => 'pl_PL', 'pol' => 'pl_PL', 'ps' => 'ps', 'pus' => 'ps', 'pt' => 'pt_PT', 'por' => 'pt_BR', 'rhg' => 'rhg', 'ro' => 'ro_RO', 'ron' => 'ro_RO', 'ru' => 'ru_RU', 'rus' => 'ru_RU', 'sah' => 'sah', 'sd' => 'snd', 'snd' => 'snd', 'si' => 'si_LK', 'sin' => 'si_LK', 'sk' => 'sk_SK', 'slk' => 'sk_SK', 'skr' => 'skr', 'sl' => 'sl_SI', 'slv' => 'sl_SI', 'sq' => 'sq', 'sqi' => 'sq', 'sr' => 'sr_RS', 'srp' => 'sr_RS', 'sv' => 'sv_SE', 'swe' => 'sv_SE', 'sw' => 'sw', 'swa' => 'sw', 'szl' => 'szl', 'ta' => 'ta_IN', 'tam' => 'ta_IN', 'te' => 'te', 'tel' => 'te', 'th' => 'th', 'tha' => 'th', 'tl' => 'tl', 'tgl' => 'tl', 'tr' => 'tr_TR', 'tur' => 'tr_TR', 'tt' => 'tt_RU', 'tat' => 'tt_RU', 'ty' => 'tah', 'tah' => 'tah', 'ug' => 'ug_CN', 'uig' => 'ug_CN', 'uk' => 'uk', 'ukr' => 'uk', 'ur' => 'ur', 'urd' => 'ur', 'uz' => 'uz_UZ', 'uzb' => 'uz_UZ', 'vi' => 'vi', 'vie' => 'vi', 'zh' => 'zh_CN', 'zho' => 'zh_CN'];
    /**
     * Convert two letter code to WordPress compatible code. E.g. `de` -> `de_DE`.
     *
     * @param string $locale
     */
    public static function twoToWordPressCompatible($locale)
    {
        return self::TWO_LETTER_TO_WORDPRESS[$locale] ?? $locale;
    }
    /**
         * Output a mapping of the available translations of WordPress compatible codes to two letter codes.
         *
         * This function should only be used to generate the hardcoded `TWO_LETTER_TO_WORDPRESS` constant
         * cause the `wp_get_available_translations` API is expensive (request wordpress.org servers).
         *
        public static function printIsoWordPressMapping() {
            require_once ABSPATH . 'wp-admin/includes/translation-install.php';
            $languages = wp_get_available_translations();
    
            $twoLetterCodes = [];
            foreach ($languages as $lang) {
                $wpCode = $lang['language'];
                foreach ($lang['iso'] as $iso) {
                    if (!isset($twoLetterCodes[$iso]) || strtolower($wpCode) === sprintf('%1$s_%1$s', $iso)) {
                        $twoLetterCodes[$iso] = $wpCode;
                    }
                }
            }
            wp_die(json_encode($twoLetterCodes));
        }*/
}
