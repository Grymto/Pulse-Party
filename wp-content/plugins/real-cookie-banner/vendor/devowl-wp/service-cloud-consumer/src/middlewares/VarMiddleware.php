<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares;

/**
 * Middleware to replace `{{var:my_var}}` from variable consumer in all string
 * properties of the template.
 * @internal
 */
class VarMiddleware extends AbstractTemplateMiddleware
{
    /**
     * Regular expression to get variable name and default value.
     *
     * @see https://regex101.com/r/aXEAvD/1
     */
    const REGEXP = '/{{var:([^}=]+)(?:=([^}]*))?}}/m';
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        // Silence is golden.
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        $variableResolver = $this->getVariableResolver();
        \array_walk_recursive($template, function (&$val, $key) use($variableResolver) {
            if (\is_string($val) && \strpos($val, '{{var:') !== \false) {
                $val = \preg_replace_callback(self::REGEXP, function ($m) use($variableResolver) {
                    $variableName = $m[1];
                    // Special cases
                    switch ($variableName) {
                        case 'consumer.contactEmail':
                            // Legacy variable rename
                            $variableName = 'consumer.providerContact.email';
                            break;
                        case 'nonce8':
                            return self::createScriptNonce(8);
                        default:
                            break;
                    }
                    if (isset($m[2])) {
                        // It has a default value
                        return $variableResolver->resolveDefault($variableName, $m[2]);
                    } else {
                        return $variableResolver->resolveRequired($variableName);
                    }
                }, $val);
            }
        });
    }
    /**
     * E.g. Facebook uses a random generated script nonce for caching purposes.
     *
     * @param int $nonceLength
     */
    public static function createScriptNonce($nonceLength = 8)
    {
        $original_string = \array_merge(\range(0, 9), \range('a', 'z'), \range('A', 'Z'));
        $original_string = \implode('', $original_string);
        return \substr(\str_shuffle($original_string), 0, $nonceLength);
    }
}
