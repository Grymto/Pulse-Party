<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\consumer;

/**
 * Put multiple `ServiceCloudConsumer` instances into a pool which guarantees to persist
 * relations e.g. `BlockerTemplate` -> `ServiceTemplate`. That means, if one consumer gets
 * invalidated, all other within this pool gets invalidated, too.
 *
 * This is useful together with `AbstractPoolMiddleware`.
 * @internal
 */
class ConsumerPool
{
    /**
     * Consumers.
     *
     * @var ServiceCloudConsumer[]
     */
    private $consumers = [];
    /**
     * C'tor.
     *
     * @param ServiceCloudConsumer[] $consumers
     */
    public function __construct($consumers)
    {
        $this->consumers = $consumers;
        foreach ($this->consumers as $consumer) {
            $consumer->addPool($this);
        }
    }
    /**
     * Invalidates all consumers.
     */
    public function invalidate()
    {
        // Only pick first one as the consumer implementation automatically invalidates all other, too
        $this->consumers[0]->retrieve(\true);
    }
    /**
     * Get consumer by class type.
     *
     * @param string $typeClass Should be the class which extends from `AbstractTemplate`
     */
    public function getConsumer($typeClass)
    {
        foreach ($this->consumers as $consumer) {
            if ($consumer->getTypeClass() === $typeClass) {
                return $consumer;
            }
        }
        return null;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getConsumers()
    {
        return $this->consumers;
    }
}
