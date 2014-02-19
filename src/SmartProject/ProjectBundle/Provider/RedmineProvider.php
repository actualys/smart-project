<?php

namespace SmartProject\ProjectBundle\Provider;

use SmartProject\ProjectBundle\Entity\Project;

/**
 * Class RedmineProvider
 *
 * @package SmartProject\ProjectBundle\Provider
 */
class RedmineProvider
{
    protected $doctrine;

    /**
     * @var \Redmine\Client
     */
    protected $client;

    /**
     * @param $doctrine
     * @param $redmine
     */
    public function __construct($doctrine, $redmine)
    {
        $this->doctrine = $doctrine;
        $this->client   = new \Redmine\Client($redmine['hostname'], $redmine['apikey']);
    }

    /**
     *
     */
    public function synchronize()
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->doctrine->getManager();
        $entities      = array();
        $offset        = 0;
        $parents       = array();

        do {
            $response = $this->client->api('project')->all(
                array(
                    'limit'  => 25,
                    'offset' => $offset,
                )
            );

            $projects = $response['projects'];
            $offset += count($projects);

            $repository = $entityManager->getRepository('SmartProjectProjectBundle:Project');

            foreach ($projects as $project) {
                /** @var Project $entity */
                $entity = $repository->findByRedmineId($project['id']);

                if (!$entity) {
                    $entity = new Project();
                    $entity->setDescription($project['description']);
                }

                $entity->setName($project['name']);
                $entity->setRedmineId($project['id']);
                $entity->setRedmineIdentifier($project['identifier']);
//                $entity->setParent(null);

                $entities[$project['id']] = $entity;

                if (isset($project['parent']['id'])) {
                    $parents[$project['id']] = $project['parent']['id'];
                }
            }

        } while ($offset < $response['total_count'] && count($projects));

//        foreach ($parents as $id => $parentId) {
//            $entities[$id]->setParent($entities[$parentId]);
//        }

        if (count($entities)) {
            foreach ($entities as $entity) {
                $entityManager->persist($entity);
            }

            $entityManager->flush();
        }
    }
} 