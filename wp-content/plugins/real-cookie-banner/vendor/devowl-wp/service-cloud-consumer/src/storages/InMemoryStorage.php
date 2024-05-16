<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\storages;

use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\AbstractTemplate;
/**
 * In-memory storage for templates. It never automatically invalidates.
 * @internal
 */
class InMemoryStorage extends AbstractStorage
{
    /**
     * Templates.
     *
     * @var AbstractTemplate[]
     */
    private $templates;
    // Documented in AbstractStorage
    public function retrieve($forceInvalidate)
    {
        return $this->templates === null ? \false : $this->templates;
    }
    // Documented in AbstractStorage
    public function retrieveBy($field, $value, $forceInvalidate)
    {
        if ($this->templates === null) {
            return \false;
        }
        if ($field === 'identifier') {
            foreach ($this->templates as $template) {
                if ($template->identifier === $value) {
                    return [$template];
                }
            }
        }
        return [];
    }
    // Documented in AbstractStorage
    public function persist($templates)
    {
        $this->templates = $templates;
    }
    // Documented in AbstractStorage
    public function shouldInvalidate()
    {
        return \false;
    }
}
