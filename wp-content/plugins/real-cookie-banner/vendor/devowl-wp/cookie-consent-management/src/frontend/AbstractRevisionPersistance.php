<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\frontend;

/**
 * Additional to `Revision`, this abstract class provides persistence settings for the revision, e.g. generating
 * the cookie name, persisting revisions to database and so on.
 * @internal
 */
abstract class AbstractRevisionPersistance
{
    /**
     * See `Revision`.
     *
     * @var Revision
     */
    private $revision;
    /**
     * Persist an revision JSON string to database.
     *
     * @param array $result `revision` and `hash`
     * @param boolean $forceNewConsent If `true` update the hash to the database to `getCurrentHash()` returns that hash
     */
    public abstract function persist($result, $forceNewConsent);
    /**
     * Persist an independent revision JSON string to database.
     *
     * @param array $result `revision` and `hash`
     */
    public abstract function persistIndependent($result);
    /**
     * Get implicit context relevant options like blog id. Implicit context variables are not populated
     * to the context, nor to the revision. Use this only if you want to modify the cookie name!
     *
     * Warning: Cookie names cannot contain any of the following '=,; \t\r\n\013\014', so please make
     * sure such characters are not stored in your value (if so, they get replaced with underscore `_`).
     *
     * @param array
     */
    public abstract function getContextVariablesImplicit();
    /**
     * Get explicit context relevant options like language code (WPML, PolyLang). If the language
     * changes, a new revision will be created or requested so they are completely independent.
     * They also get populated to the generated revision.
     *
     * Warning: Cookie names cannot contain any of the following '=,; \t\r\n\013\014', so please make
     * sure such characters are not stored in your value (if so, they get replaced with underscore `_`).
     *
     * @return array
     */
    public abstract function getContextVariablesExplicit();
    /**
     * Get the current active revision hash. Can also return a falsy value when no hash is currently generated.
     */
    public abstract function getCurrentHash();
    /**
     * Get context relevant options as string so they can be used as cookie name or option name.
     *
     * @param boolean $implicit If `true`, implicit context variables are parsed, otherwise explicit context variables
     */
    public function getContextVariablesString($implicit = \false)
    {
        $value = \json_encode($implicit ? $this->getContextVariablesImplicit() : $this->getContextVariablesExplicit());
        $value = \str_replace(['{', '"', '}', '[', ']'], '', $value);
        // Warning: Cookie names cannot contain any of the following '=,; \t\r\n\013\014'
        $value = \str_replace(['=', ',', ';'], '_', $value);
        return $value;
    }
    /**
     * Overwrite this method to modify the revision object. This allows you to add additional data to the revision.
     *
     * @param array $revision
     * @return array
     */
    public function alterRevision(&$revision)
    {
        return $revision;
    }
    /**
     * Overwrite this method to modify the independent revision object. This allows you to add additional data to the revision.
     *
     * @param array $revision
     * @return array
     */
    public function alterRevisionIndependent(&$revision)
    {
        return $revision;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getRevision()
    {
        return $this->revision;
    }
    /**
     * Setter.
     *
     * @param Revision $revision
     * @codeCoverageIgnore
     */
    public function setRevision($revision)
    {
        $this->revision = $revision;
    }
}
