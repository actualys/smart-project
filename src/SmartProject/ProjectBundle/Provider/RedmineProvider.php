<?php

namespace SmartProject\ProjectBundle\Provider;

use SmartProject\ProjectBundle\Entity\Redmine\Project;

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
     * @param string $hostname
     * @param string $apikey
     */
    public function __construct($doctrine, $hostname, $apikey)
    {
        $this->doctrine = $doctrine;
        $this->client   = new \Redmine\Client($hostname, $apikey);
    }

    /**
     *
     */
    public function synchronize()
    {
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

            foreach ($projects as $project) {
                $entity = $entityManager->find('SmartProjectProjectBundle:Redmine\Project', $project['id']);

                if (!$entity) {
                    $entity = new Project();
                    $entity->setId($project['id']);
                }

                $entity->setIdentifier($project['identifier']);
                $entity->setName($project['name']);
                $entity->setDescription($project['description']);
                $entity->setCreatedOn(new \DateTime($project['created_on']));
                $entity->setUpdatedOn(new \DateTime($project['updated_on']));
                $entity->setParent(null);

                $entities[$project['id']] = $entity;

                if (isset($project['parent']['id'])) {
                    $parents[$project['id']] = $project['parent']['id'];
                }
            }

        } while ($offset < $response['total_count'] && count($projects));

        foreach ($parents as $id => $parentId) {
            $entities[$id]->setParent($entities[$parentId]);
        }

        if (count($entities)) {
            foreach ($entities as $entity) {
                $entityManager->persist($entity);
            }

            $entityManager->flush();
        }
    }
} 