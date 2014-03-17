<?php

namespace SmartProject\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use SmartProject\TimesheetBundle\Entity\Task\ProjectInterface;

/**
 * Project
 *
 * @ORM\Entity(repositoryClass="SmartProject\ProjectBundle\Entity\ProjectRepository")
 */
class Project extends BaseProject
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_PROJECT;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        $parent = $this->getParent();

        if ($parent instanceof Client) {
            return $parent;
        } elseif ($parent instanceof Project) {
            return $parent->getClient();
        }

        return null;
    }

    /**
     * @param Client $client
     *
     * @return Project
     * @throws \Exception
     */
    public function setClient(Client $client)
    {
//        if ($this->getParent() instanceof Project) {
//            throw new \Exception('This project is not the root project.');
//        }

        return $this->setParent($client);
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function addSubProject(Project $project)
    {
        return $this->addChildren($project);
    }

    /**
     * @param Project $project
     */
    public function removeSubProject(Project $project)
    {
        $this->removeChildren($project);
    }

    /**
     * @return ArrayCollection
     */
    public function getSubProjects()
    {
        $children  = $this->getChildren();
        $projects = new ArrayCollection();

        foreach ($children as $child) {
            if ($child instanceof Project) {
                $projects->add($child);
            }
        }

        return $projects;
    }

    /**
     * Add contract
     *
     * @param Contract $contract
     *
     * @return Project
     */
    public function addContract(Contract $contract)
    {
        $this->addChildren($contract);

        return $this;
    }

    /**
     * Remove contract
     *
     * @param Contract $contract
     */
    public function removeContract(Contract $contract)
    {
        $this->removeChildren($contract);
    }

    /**
     * Get contracts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContracts()
    {
        $children  = $this->getChildren();
        $contracts = new ArrayCollection();

        foreach ($children as $child) {
            if ($child instanceof Contract) {
                $contracts->add($child);
            }
        }

        return $contracts;
    }
}