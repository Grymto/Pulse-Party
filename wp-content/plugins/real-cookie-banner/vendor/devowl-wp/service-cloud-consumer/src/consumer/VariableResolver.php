<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
use Exception;
/**
 * Allows to resolve variables and define if they are required or optional with a default.
 * It also allows to resolve variables by `closure(VariableResolver $r)` instead of value.
 *
 * Known variables (`= [default] | * (required)`):
 *
 * #### Consumer data
 *
 * ```txt
 * consumer.privacyPolicyUrl            = * string
 * consumer.legalNoticeUrl              = * string
 * consumer.provider                    = * string
 * consumer.providerContact.phone       = * string
 * consumer.providerContact.email       = * string
 * consumer.providerContact.link        = * string
 * consumer.host.main                   = * string
 * consumer.host.main+subdomains        = * string
 * consumer.host.current                = * string
 * consumer.host.current+protocol       = * string
 * consumer.host.current+subdomains     = * string
 * template.fbPixel.scriptLocale        = * Script locale used for Facebook pixel SDK js
 * ```
 *
 * #### Created templates
 *
 * Array of existing / created blocker and services within the consumer environment. You do not need to fill all properties of the template object,
 * but `consumerData['id']` and `identifier` should be filled to make sure that the internal middlewares are working as expected.
 *
 * ```txt
 * created.{TemplateClass::class}       = * TemplateClass[]
 * created.global.{TemplateClass::class}    = * TemplateClass[]
 * tcfVendors.created = Array<{ vendorId: number; vendorConfigurationId: number; }>
 * ```
 *
 * What is the difference between `created.` and `created.global.`? In a consumer environment like e.g. WordPress and WPML as plugin installed,
 * `created.` should only return the elements for the current language (current context). The `created.global.` should return all entries across
 * all languages.
 *
 * ### Misc
 *
 * ```txt
 * blocker.consumer   = * Service cloud consumer for blocker templates
 * service.consumer   = * Service cloud consumer for service templates
 * tier               = * "free" | "pro"
 * manager            = * "none" | "googleTagManager" | "googleTagManagerWithGcm" | "matomoTagManager"
 * serviceScan        = Record<string (identifier), { foundCount: number; foundOnSitesCount: number; lastScanned: string; }>;
 * serviceScanIgnored = string[]
 * isTcfActive        = boolean
 * ```
 *
 * ### One-of mechanism
 *
 * Closure which needs to return boolean when the passed statement is fulfilled (e.g. checks if a setting or plugin is active).
 * When you return e.g. a string it will considered as fulfilled and replace the statement for the `$fulfilledStatement` variable.
 *
 * ```txt
 * oneOf      = * (statement: string, template?: AbstractTemplate) => false | true | mixed
 * ```
 *
 * ### Localization
 *
 * ```txt
 * i18n.disabled                                                              = "Disabled"
 * i18n.ContentTypeButtonTextMiddleware.loadContent                           = "Load content"
 * i18n.ContentTypeButtonTextMiddleware.loadMap                               = "Load map"
 * i18n.ContentTypeButtonTextMiddleware.loadForm                              = "Load form"
 * i18n.ExistsMiddleware.alreadyCreated                                       = "Already created"
 * i18n.ExistsMiddleware.blockerAlreadyCreatedTooltip                         = "You have already created a Content Blocker with this template."
 * i18n.ExistsMiddleware.serviceAlreadyCreatedTooltip                         = "You have already created a Service (Cookie) with this template."
 * i18n.ManagerMiddleware.tooltip                                             = "This service template is optimized to work with %s."
 * i18n.ManagerMiddleware.disabledTooltip                                     = "Please activate %s in settings to use this template."
 * i18n.ServiceAvailableBlockerTemplatesMiddleware.tooltip                    = "A suitable content blocker for this service can be created automatically."
 * i18n.GroupMiddleware.group.essential                                       = "Essential"
 * i18n.GroupMiddleware.group.functional                                      = "Functional"
 * i18n.GroupMiddleware.group.statistics                                      = "Statistics"
 * i18n.GroupMiddleware.group.marketing                                       = "Marketing"
 * i18n.OneOfMiddleware.disabledTooltip                                       = "This template is currently disabled because a dependency component is not installed or the desired function is not active."
 * i18n.TcfMiddleware.disabled                                                = "TCF required"
 * i18n.TcfMiddleware.disabledTooltip                                         = "This template requires the integration of TCF, as the provider of this template uses this standard. Please activate this in the settings to be able to block this service."
 * ```
 * @internal
 */
class VariableResolver
{
    private $consumer;
    /**
     * Registered variable data.
     *
     * @var mixed[]
     */
    private $data = [];
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer $consumer
     */
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
    }
    /**
     * Add and register a variable with a given value.
     *
     * @param string $key
     * @param mixed $value Can be also a closure which gets called with the following arguments: `VariableResolver $resolver`
     */
    public function add($key, $value)
    {
        $this->data[$key] = $value;
    }
    /**
     * Resolve a variable by key with a default value.
     *
     * @param string $key
     * @param mixed $default
     */
    public function resolveDefault($key, $default = null)
    {
        try {
            return $this->resolveRequired($key);
        } catch (Exception $e) {
            return $default;
        }
    }
    /**
     * Resolve a variable by key and require it.
     *
     * @param string $key
     * @throws Exception
     */
    public function resolveRequired($key)
    {
        if (isset($this->data[$key])) {
            $value = $this->data[$key];
            if (!\in_array($key, ['oneOf'], \true) && !\is_string($value) && \is_callable($value)) {
                // Resolve by closure and cache value
                $value = $value($this);
                $this->data[$key] = $value;
            }
            return $value;
        }
        throw new Exception(\sprintf('Please provide a variable with name "%s" in the consumers variable resolver.', $key));
    }
    /**
     * Check an array of statements if one of them resolves truly with the required variable `oneOf`.
     *
     * @param string[] $statements
     * @param AbstractTemplate $template
     * @param string $fulfilledStatement Automatically gets filled with the first fulfilled statement
     */
    public function resolveOneOf($statements, $template, &$fulfilledStatement = null)
    {
        $callable = $this->resolveRequired('oneOf');
        foreach ($statements as $statement) {
            $callableResult = $callable($statement, $template);
            if ($callableResult !== \false) {
                $fulfilledStatement = $callableResult === \true ? $statement : $callableResult;
                return \true;
            }
        }
        return \false;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
}
