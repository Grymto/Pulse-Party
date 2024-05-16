<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\datasources;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * Retrieve templates directly from a configured JSON or `AbstractTemplate` instances.
 * @internal
 */
class LocalDataSource extends AbstractDataSource
{
    private $createdDefaults = \false;
    /**
     * Templates.
     *
     * @var AbstractTemplate[]
     */
    private $templates = [];
    // Documented in AbstractDataSource
    public function retrieve()
    {
        if (!$this->createdDefaults) {
            $this->createDefaults();
            $this->createdDefaults = \true;
        }
        return $this->templates;
    }
    /**
     * Add template to this data source.
     *
     * @param AbstractTemplate $template
     */
    public function add($template)
    {
        $this->templates[] = $template;
    }
    /**
     * Overwrite this function which adds defaults to the local data source.
     */
    public function createDefaults()
    {
        // Silence is golden.
    }
}
