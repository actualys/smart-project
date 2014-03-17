<?php

namespace SmartProject\SyncBundle\Providers;

/**
 * Interface ProviderInterface
 *
 * @package SmartProject\SyncBundle\Providers
 */
interface ProviderInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return boolean
     */
    public function execute();
} 