<?php

namespace SmartProject\ProjectBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use SmartProject\TimesheetBundle\Entity\Task\ClientInterface;

/**
 * Client
 *
 * @ORM\Entity(repositoryClass="SmartProject\ProjectBundle\Entity\ClientRepository")
 */
class Client extends BaseProject
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_CLIENT;
    }

    /**
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\BaseProjectInterface
     */
    public function getClient()
    {
        return null;
    }

    /**
     * Add project
     *
     * @param \SmartProject\ProjectBundle\Entity\Project $project
     * @return Client
     */
    public function addProject(Project $project)
    {
        $this->addChildren($project);
        $project->setParent($this);

        return $this;
    }

    /**
     * Remove project
     *
     * @param \SmartProject\ProjectBundle\Entity\Project $project
     */
    public function removeProject(Project $project)
    {
        $this->removeChildren($project);
        $project->setParent(null);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->getChildren();
    }
}