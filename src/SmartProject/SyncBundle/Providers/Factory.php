<?php

namespace SmartProject\SyncBundle\Providers;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class Factory
 *
 * @package SmartProject\SyncBundle\Providers
 */
class Factory extends ContainerAware
{
    /**
     * @var array
     */
    protected $clients;

    /**
     * @var array
     */
    protected $providers;

    /**
     * @var array
     */
    protected $instances;

    /**
     * @param array $providers
     * @param array $clients
     */
    public function __construct($providers, $clients)
    {
        $this->clients  = $clients;
        $this->providers = $providers;

        $this->instances = array();
    }

    /**
     * @param string $providerCode
     *
     * @return boolean
     * @throws \Exception
     */
    public function getInstance($providerCode)
    {
        if (isset($this->instances[$providerCode])) {
            return $this->instances[$providerCode];
        }

        if (!isset($this->clients[$providerCode])) {
            throw new \Exception('Unknown provider code');
        }

        $settings = $this->clients[$providerCode];
        $type     = $settings['type'];

        if (!isset($this->providers[$type])) {
            throw new \Exception('Unknown provider type');
        }

        $className = $this->providers[$type];
        /** @var ContainerAwareInterface $instance */
        $instance = new $className($settings);
        $instance->setContainer($this->container);

        return ($this->instances[$providerCode] = $instance);
    }
}
