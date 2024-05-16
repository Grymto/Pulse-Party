<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\CookieConsentManagement\services;

use stdClass;
/**
 * Provider contact details for a service.
 * @internal
 */
class ProviderContact
{
    /**
     * Phone contact.
     *
     * @var string
     */
    private $phone;
    /**
     * Email address.
     *
     * @var string
     */
    private $email;
    /**
     * Contact URL.
     *
     * @var string
     */
    private $link;
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getPhone()
    {
        return $this->phone;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getLink()
    {
        return $this->link;
    }
    /**
     * Setter.
     *
     * @param string $phone
     * @codeCoverageIgnore
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    /**
     * Setter.
     *
     * @param string $email
     * @codeCoverageIgnore
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    /**
     * Setter.
     *
     * @param string $link
     * @codeCoverageIgnore
     */
    public function setLink($link)
    {
        $this->link = $link;
    }
    /**
     * Create a JSON representation of this object.
     */
    public function toJson()
    {
        return ['phone' => $this->phone, 'email' => $this->email, 'link' => $this->link];
    }
    /**
     * Generate a `ProviderContact` object from an array.
     *
     * @param array $data
     */
    public static function fromJson($data)
    {
        if ($data instanceof stdClass) {
            $data = (array) $data;
        }
        $instance = new self();
        $instance->setPhone($data['phone'] ?? '');
        $instance->setEmail($data['email'] ?? '');
        $instance->setLink($data['link'] ?? '');
        return $instance;
    }
}
