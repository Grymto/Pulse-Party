<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\tcf;

/**
 * Additional to `AbstractTcf`, this abstract class provides persistence for the TCF GVL vendor list, e.g. by reading
 * vendors, declarations and so on from the database.
 * @internal
 */
abstract class AbstractGvlPersistance
{
    /**
     * Get the latest GVL, TCF Policy and vendor list versions.
     *
     * @return int[]
     */
    public abstract function getLatestVersions();
    /**
     * Get the language which is used for the GVL. It needs to be a two-letter ISO code, see also
     * `self::fourLetterLanguageCodeToTwoLetterCode()`.
     *
     * @return string
     */
    public abstract function getCurrentLanguage();
    /**
     * Query all available declaration of the latest GVL and TCF policy version for the
     * current language. If the language does not exist for the current TCF version, let's
     * fallback to the default TCF version.
     *
     * Additional arguments:
     * - [`onlyReturnDeclarations`]: (boolean) Default to `false`, do not populate `gvlSpecificationVersion`, ...
     *
     * @param array $args Additional arguments, see description of `purposes`
     */
    public abstract function allDeclarations($args = []);
    /**
     * Query available stacks of the latest GVL and TCF policy version for the
     * current language. If the language does not exist for the current TCF version, let's
     * fallback to the default TCF version.
     *
     * Arguments:
     *
     * - [`gvlSpecificationVersion`]: (int) Default to latest
     * - [`tcfPolicyVersion`]: (int) Default to latest
     * - [`language`]: (string) Default to current
     *
     * @param array $args Additional arguments, see description
     * @return array
     */
    public abstract function stacks($args = []);
    /**
     * Fetch a list of vendors by arguments and return an array of vendors matching
     * the schema of the official `vendor-list.json`.
     *
     * Arguments:
     *
     * - [`in`]: (int[]) Only read this vendors (`WHERE IN`)
     * - [`gvlSpecificationVersion`]: (int) Default to latest
     * - [`tcfPolicyVersion`]: (int) Default to latest
     * - [`vendorListVersion`]: (int) Default to latest
     * - [`language`]: (string) Default to current
     *
     * @see https://vendor-list.consensu.org/v3/vendor-list.json
     * @param array $args
     * @return array[]
     */
    public abstract function vendors($args = []);
}
