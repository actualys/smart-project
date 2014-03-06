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
                $entity = $repository->findBy(array('redmineId' => $project['id']));

                if (!$entity) {
                    $entity = new Project();
                    $entity->setDescription($project['description']);
                } else {
                    $entity = reset($entity);
                }

                $entity->setName($project['name']);
                $entity->setRedmineId($project['id']);
                $entity->setRedmineIdentifier($project['identifier']);

                $entities[$project['id']] = $entity;
            }

        } while ($offset < $response['total_count'] && count($projects));

        if (count($entities)) {
            foreach ($entities as $entity) {
                $entityManager->persist($entity);
            }

            $entityManager->flush();
        }
    }
} 