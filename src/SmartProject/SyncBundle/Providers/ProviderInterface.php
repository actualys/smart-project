<?php

namespace SmartProject\SyncBundle\Providers;

use SmartProject\ProjectBundle\Entity\BaseProject;

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

    /**
     * @param BaseProject $project
     *
     * @return mixed
     */
    public function getUrlForProject(BaseProject $project);
} 