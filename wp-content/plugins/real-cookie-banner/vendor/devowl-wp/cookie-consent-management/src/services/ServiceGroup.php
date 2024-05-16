<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services;

/**
 * A service group aggregates a list of services with a name and a short description.
 * @internal
 */
class ServiceGroup
{
    /**
     * The ID of the service group when it got created in a stafeul way.
     *
     * @var int
     */
    private $id = 0;
    /**
     * Name.
     *
     * @var string
     */
    private $name = '';
    /**
     * Slug of the name.
     *
     * @var string
     */
    private $slug = '';
    /**
     * Description.
     *
     * @var string
     */
    private $description = '';
    /**
     * Is this the essential group?
     *
     * @var boolean
     */
    private $isEssential = \false;
    /**
     * The services of the service group.
     *
     * @var Service[]
     */
    private $items = [];
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getSlug()
    {
        return $this->slug;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function isEssential()
    {
        return $this->isEssential;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getItems()
    {
        return $this->items;
    }
    /**
     * Setter.
     *
     * @param int $id
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Setter.
     *
     * @param string $name
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * Setter.
     *
     * @param string $slug
     * @codeCoverageIgnore
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }
    /**
     * Setter.
     *
     * @param string $description
     * @codeCoverageIgnore
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * Setter.
     *
     * @param string $isEssential
     * @codeCoverageIgnore
     */
    public function setIsEssential($isEssential)
    {
        $this->isEssential = $isEssential;
    }
    /**
     * Setter.
     *
     * @param Service[] $items
     * @codeCoverageIgnore
     */
    public function setItems($items)
    {
        $this->items = $items;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['id' => $this->id, 'name' => $this->name, 'slug' => $this->slug, 'description' => $this->description, 'isEssential' => $this->isEssential, 'items' => \array_map(function ($item) {
            return $item->toJson();
        }, $this->items)];
    }
    /**
     * Generate a `ServiceGroup` object from an array.
     *
     * @param array $data
     */
    public static function fromJson($data)
    {
        $instance = new self();
        $instance->setId($data['id'] ?? 0);
        $instance->setName($data['name'] ?? '');
        $instance->setSlug($data['slug'] ?? '');
        $instance->setDescription($data['description'] ?? '');
        $instance->setIsEssential($data['isEssential'] ?? \false);
        $instance->setItems(\array_map(function ($data) {
            return Service::fromJson($data);
        }, $data['items'] ?? []));
        return $instance;
    }
}
