<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker\ContentTypeButtonTextMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker\DisableProFeaturesInFreeMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker\FlatRulesMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\blocker\ResolveServiceTemplatesMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\ExistsMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\OneOfMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\ScanResultsMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\SuccessorMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\TcfMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\VarMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\VersionsMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
/**
 * Predefined service cloud consumer for `BlockerTemplate` with all required middlewares registered.
 * @internal
 */
class BlockerConsumer extends ServiceCloudConsumer
{
    /**
     * C'tor.
     */
    public function __construct()
    {
        parent::__construct(BlockerTemplate::class);
        $this->addMiddleware(new ExistsMiddleware($this));
        $this->addMiddleware(new OneOfMiddleware($this));
        $this->addMiddleware(new FlatRulesMiddleware($this));
        $this->addMiddleware(new ResolveServiceTemplatesMiddleware($this));
        $this->addMiddleware(new ContentTypeButtonTextMiddleware($this));
        $this->addMiddleware(new DisableProFeaturesInFreeMiddleware($this));
        $this->addMiddleware(new VarMiddleware($this));
        $this->addMiddleware(new ScanResultsMiddleware($this));
        $this->addMiddleware(new VersionsMiddleware($this));
        $this->addMiddleware(new TcfMiddleware($this));
        $this->addMiddleware(new SuccessorMiddleware($this));
    }
}
