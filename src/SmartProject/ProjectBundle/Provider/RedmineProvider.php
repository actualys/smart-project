<?php

namespace SmartProject\ProjectBundle\Provider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Redmine\Client;
use SmartProject\ProjectBundle\Entity\Contract;
use SmartProject\ProjectBundle\Entity\Project;
use SmartProject\ProjectBundle\Entity\ProjectRepository;

/**
 * Class RedmineProvider
 *
 * @package SmartProject\ProjectBundle\Provider
 */
class RedmineProvider
{
    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @var \Redmine\Client
     */
    protected $client;

    /**
     * @param $doctrine
     * @param $redmine
     */
    public function __construct(Registry $doctrine, $redmine)
    {
        $this->doctrine = $doctrine;
        $this->client   = new Client($redmine['hostname'], $redmine['apikey']);
    }

    /**
     *
     */
    public function synchronize()
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->doctrine->getManager();
        $entities      = array();
        $parents       = array();
        $offset        = 0;

        /** @var ProjectRepository $repository */
        $repository = $entityManager->getRepository('SmartProjectProjectBundle:Project');

        do {
            $response = $this->client->api('project')->all(
                array(
                    'limit' => 25,
                    'offset' => $offset,
                )
            );

            $projects = $response['projects'];
            $offset += count($projects);

            foreach ($projects as $project) {
                /** @var array $entity */
                $entity = $repository->findBy(array('redmineId' => $project['id']));

                if (!$entity) {
                    /** @var Project $entity */
                    $entity = new Project();
                    $entity->setDescription($project['description']);
                } else {
                    $entity = reset($entity);
                    /** @var Project $entity */
                }

                $entity->setName(trim($project['name']));
                $entity->setRedmineId($project['id']);
                $entity->setRedmineIdentifier($project['identifier']);
                $entity->setParent(null);

                if ($entity->getContracts()->isEmpty()) {
                    $contract = new Contract();
                    $contract->setName('default');
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
                    $parent->addChildren($project);
                    $project->setParent($parent);
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
                /** @var Project $parent */
                $parent = $entities[$parentId];
                if (null === $parent->getParent() && $parent->getChildren()) {
                    $repository->reorder($parent, 'name', 'asc', false);
                }
            }
        }

        $repository->verify();
    }
}
