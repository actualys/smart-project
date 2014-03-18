<?php

namespace SmartProject\SyncBundle\Providers;

use Redmine\Client;
use SmartProject\ProjectBundle\Entity\BaseProject;
use SmartProject\ProjectBundle\Entity\Contract;
use SmartProject\ProjectBundle\Entity\Project;
use SmartProject\ProjectBundle\Entity\ProjectRepository;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class Redmine
 *
 * @package SmartProject\SyncBundle\Providers
 */
class Redmine extends ContainerAware implements ProviderInterface
{
    const PROVIDER_NAME = 'redmine';

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param array $settings
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
        $this->client   = new Client($settings['config']['hostname'], $settings['config']['apikey']);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->settings['label'];
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if (!$this->settings['enabled']) {
            return false;
        }

        // TODO : move code into ProductBundle when available
        //$productManager = $this->container->get('');

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->container->get('doctrine')->getManager();
        $entities      = array();
        $parents       = array();
        $offset        = 0;

        try {
            $entityManager->beginTransaction();

            /** @var ProjectRepository $repository */
            $repository = $entityManager->getRepository('SmartProjectProjectBundle:Project');

            do {
                $response = $this->client->api('project')->all(
                    array(
                        'limit'  => 25,
                        'offset' => $offset,
                    )
                );

                $projects = $response['projects'];
                $offset += count($projects);

                foreach ($projects as $project) {
                    /** @var array $entity */
                    $entity = $repository->findBy(
                        array(
                            'syncProvider' => self::PROVIDER_NAME,
                            'syncId' => $project['id']
                        )
                    );

                    if (!$entity) {
                        /** @var Project $entity */
                        $entity = new Project();
                        $entity->setDescription($project['description']);
                    } else {
                        $entity = reset($entity);
                        /** @var Project $entity */
                    }

                    $entity->setName(trim($project['name']));
                    $entity->setSyncProvider(self::PROVIDER_NAME);
                    $entity->setSyncId($project['id']);
                    $entity->setSyncIdentifier($project['identifier']);

                    if (!$entity->getParent() instanceof Client) {
                        $entity->setParent(null);
                    }

                    if ($entity->getContracts()->isEmpty()) {
                        $contract = new Contract();
                        $contract->setName('default contract');
                        $entity->addContract($contract);
                    }

                    $entities[$project['id']] = $entity;

                    if (isset($project['parent']['id'])) {
                        $parents[$project['parent']['id']][] = $entity;
                    }
                }

            } while ($offset < $response['total_count'] && count($projects));

            uasort(
                $entities,
                function (Project $a, Project $b) {
                    return strcasecmp($a->getName(), $b->getName());
                }
            );

            foreach ($parents as $parentId => $projects) {
                if (isset($entities[$parentId])) {
                    /** @var Project $parent */
                    $parent = $entities[$parentId];

                    // Set new positions
                    /** @var Project $project */
                    foreach ($projects as $project) {
                        $parent->addSubProject($project);
                    }
                }
            }

            if (count($entities)) {
                foreach ($entities as $entity) {
                    $entityManager->persist($entity);
                }

                $entityManager->flush();
            }

            foreach ($parents as $parentId => $projects) {
                if (isset($entities[$parentId])) {
                    /** @var BaseProject $parent */
                    $parent = $entities[$parentId];
                    if (null === $parent->getParent()) {
                        $repository->reorder($parent, 'name', 'asc', false);
                    }
                }
            }

            $repository->verify();
            $entityManager->commit();

        } catch (\Exception $e) {
            $entityManager->rollback();
        }

        return true;
    }

    /**
     * @param BaseProject $project
     *
     * @return mixed
     */
    public function getUrlForProject(BaseProject $project)
    {
        return trim($this->settings['config']['hostname'], '/ ') . '/projects/' . $project->getSyncIdentifier();
    }
}
