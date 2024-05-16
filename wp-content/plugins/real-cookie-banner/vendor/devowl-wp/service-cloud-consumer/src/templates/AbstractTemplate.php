<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer\ServiceCloudConsumer;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractConsumerMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
/**
 * Abstract implementation of a single template with common attributes.
 *
 * Due to its nature of API responses and flexibility, the properties are loose (`public`)
 * and does not have a validation.
 * @internal
 */
abstract class AbstractTemplate
{
    private $consumer;
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const TIER_PRO = 'pro';
    const TIER_FREE = 'free';
    /**
     * Meta data: Language in 2-char.
     *
     * @var string
     */
    public $language;
    /**
     * Meta data: name
     *
     * @var string
     */
    public $name;
    /**
     * Meta data: headline.
     *
     * @var string
     */
    public $headline;
    /**
     * Meta data: sub headline.
     *
     * @var string
     */
    public $subHeadline;
    /**
     * Meta data: external ID within service cloud.
     *
     * @var int
     */
    public $id;
    /**
     * Meta data: Unique identifier within service cloud.
     *
     * @var string
     */
    public $identifier;
    /**
     * Meta data: headline.
     *
     * @var int
     */
    public $version;
    /**
     * A list of identifiers that replaces this template, as the old template has been deleted.
     *
     * @var array[]
     */
    public $successorOfIdentifierInfo = [];
    /**
     * Meta data: rules overwriting the `isDisabled` property.
     *
     * @var string[]
     */
    public $enabledWhenOneOf = [];
    /**
     * Meta data: only store the template but never show e.g. in frontend.
     *
     * @var int
     */
    public $isHidden;
    /**
     * Meta data: rules overwriting the `consumerData['isRecommended']` property.
     *
     * @var string[]
     */
    public $recommendedWhenOneOf = [];
    /**
     * Meta data: when this template got extended this is the parent identifier.
     *
     * @var string
     */
    public $extendsIdentifier;
    /**
     * Meta data: status within the service cloud (`published`, `draft`).
     *
     * @var string
     */
    public $status;
    /**
     * Meta data: creation date and time of template version (service cloud saves templates
     * immutable so each new version gets an own updated `createdAt` -> no `modifiedAt` needed).
     *
     * @var int
     */
    public $createdAt;
    /**
     * Meta data: Can be `free` or `pro`.
     *
     * @var string
     */
    public $tier;
    /**
     * Meta data: logo of template.
     */
    public $logoUrl;
    /**
     * Calculated consumer data which can be filled by middlewares.
     *
     * Predefined data:
     *
     * ```
     * tags                     = string[]
     * -- List of tags (like badges), key = Badge text, value = Tooltip text
     *
     * isCreated                = boolean
     * -- Is the template created in the consumer environment?
     *
     * id                       = int
     * -- ID of the record within the consumer environment using this template
     *
     * rules                    = string[]
     * -- Flattened string array of all rule-expressions
     *
     * isDisabled               = boolean
     * -- Is this template enabled within this consumer environment (e.g. WordPress plugin active?).
     *
     * isRecommended            = boolean
     * -- Is this template recommended within this consumer environment (e.g. Cloudflare detected).
     *
     * scan                     = { foundCount: number; foundOnSitesCount: number; lastScanned?: string; }
     * -- Scan results for a given template by identifier.
     *
     * isIgnored                = boolean
     * -- Is this template ignored within this consumer environment (e.g. ignored in scanner results).
     *
     * successorOf              = Array<{ identifier: string; id: int; }>
     * -- If a template is a successor to another template, this array holds infos about the ID of the record within the consumer environment using this template
     * ```
     *
     * @var mixed[]
     */
    public $consumerData = ['tags' => []];
    /**
     * Original, before running any middleware, template data. This is useful for external data sources which should
     * really be contaced e.g. once a day and a invalidation should only "retrigger" recalculation of middlewares.
     * This data can be for example be saved in a Redis object cache or database column.
     *
     * @var array
     */
    private $beforeMiddleware = null;
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer $consumer
     */
    public function __construct($consumer)
    {
        $this->consumer = $consumer;
        // Populate template with defaults
        $this->fromArray([]);
    }
    // Templates are cloned, so deep clone the whole template...
    public function __clone()
    {
        // Silence is golden.
    }
    /**
     * Modifies the template data through middleware and e.g. replaces content, variables, ...
     *
     * @return static
     */
    public function use()
    {
        // Never modify the original stored item for usage
        $clone = clone $this;
        $this->getConsumer()->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) use($clone) {
            $middleware->beforeUseTemplate($clone);
        });
        $this->getConsumer()->runMiddleware(AbstractTemplateMiddleware::class, function ($middleware) use($clone) {
            $middleware->beforeUsingTemplate($clone);
        });
        $this->getConsumer()->runMiddleware(AbstractConsumerMiddleware::class, function ($middleware) use($clone) {
            $middleware->afterUseTemplate($clone);
        });
        return $clone;
    }
    /**
     * Run all `beforeRetrievingTemplate` middlewares.
     */
    public function retrieved()
    {
        $this->getConsumer()->runMiddleware(AbstractTemplateMiddleware::class, function ($middleware) {
            $middleware->beforeRetrievingTemplate($this);
        });
    }
    /**
     * Override all properties from an array.
     *
     * @param array $arr
     */
    public function fromArray($arr)
    {
        if (\is_array($arr)) {
            $this->language = $arr['language'] ?? null;
            $this->name = \is_string($arr['name'] ?? null) ? $arr['name'] : '';
            $this->headline = \is_string($arr['headline'] ?? null) ? $arr['headline'] : '';
            $this->subHeadline = \is_string($arr['subHeadline'] ?? null) ? $arr['subHeadline'] : '';
            $this->id = \is_numeric($arr['id'] ?? null) ? \intval($arr['id']) : 0;
            $this->identifier = \is_string($arr['identifier'] ?? null) ? $arr['identifier'] : '';
            $this->version = \is_numeric($arr['version'] ?? null) ? \intval($arr['version']) : 1;
            $this->successorOfIdentifierInfo = \is_array($arr['successorOfIdentifierInfo'] ?? null) ? $arr['successorOfIdentifierInfo'] : [];
            $this->enabledWhenOneOf = \is_array($arr['enabledWhenOneOf'] ?? null) ? $arr['enabledWhenOneOf'] : [];
            $this->isHidden = \boolval($arr['isHidden'] ?? null);
            $this->recommendedWhenOneOf = \is_array($arr['recommendedWhenOneOf'] ?? null) ? $arr['recommendedWhenOneOf'] : [];
            $this->extendsIdentifier = \is_string($arr['extendsIdentifier'] ?? null) ? $arr['extendsIdentifier'] : null;
            $this->status = \in_array($arr['status'] ?? null, [self::STATUS_DRAFT, self::STATUS_PUBLISHED], \true) ? $arr['status'] : self::STATUS_PUBLISHED;
            $this->createdAt = \is_string($arr['createdAt'] ?? null) ? \strtotime($arr['createdAt']) : \time();
            $this->tier = \in_array($arr['tier'] ?? null, [self::TIER_FREE, self::TIER_PRO], \true) ? $arr['tier'] : self::TIER_FREE;
            $this->logoUrl = \is_string($arr['logoUrl'] ?? null) ? $arr['logoUrl'] : null;
            $this->consumerData = \is_array($arr['consumerData'] ?? null) ? $arr['consumerData'] : $this->consumerData;
        }
    }
    /**
     * Memoize current state of template so it can be retrieved with `getBeforeMiddleware` after running all middlewares.
     */
    public function memoizeBeforeMiddleware()
    {
        $this->beforeMiddleware = self::toArray($this);
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
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getBeforeMiddleware()
    {
        return $this->beforeMiddleware;
    }
    /**
     * Output this service template as array. This is statically available to make usage with
     * `array_map` more easier.
     *
     * @param AbstractTemplate $template
     */
    public static function toArray($template)
    {
        $res = \json_decode(\json_encode($template));
        $res->createdAt = \gmdate('c', $res->createdAt);
        return $res;
    }
    /**
     * Output multiple service templates as array representation.
     *
     * @param AbstractTemplate[] $templates
     */
    public static function toArrays($templates)
    {
        return \array_map([self::class, 'toArray'], $templates);
    }
}
