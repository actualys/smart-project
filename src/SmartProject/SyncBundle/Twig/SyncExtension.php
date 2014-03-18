<?php

namespace SmartProject\SyncBundle\Twig;

use SmartProject\ProjectBundle\Entity\Project;
use SmartProject\SyncBundle\Providers\Factory;
use SmartProject\SyncBundle\Providers\ProviderInterface;

/**
 * Class SyncExtension
 *
 * @package SmartProject\FrontBundle\Twig
 */
class SyncExtension extends \Twig_Extension
{
    /**
     * @var Factory
     */
    protected $synchronize;

    /**
     *
     */
    public function __construct(Factory $synchronize)
    {
        $this->synchronize = $synchronize;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('sync_url', array($this, 'syncUrlFunction')),
        );
    }

    /**
     * @param Project $project
     *
     * @return string
     */
    public function syncUrlFunction(Project $project)
    {
        try {
            /** @var ProviderInterface $provider */
            $provider = $this->synchronize->getInstance($project->getSyncProvider());

            return $provider->getUrlForProject($project);

        } catch(\Exception $e) {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartproject_sync';
    }
} 
