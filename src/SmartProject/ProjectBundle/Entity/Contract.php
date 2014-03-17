<?php

namespace SmartProject\ProjectBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use SmartProject\TimesheetBundle\Entity\Task\ContractInterface;

/**
 * Contract
 *
 * @ORM\Entity(repositoryClass="SmartProject\ProjectBundle\Entity\ContractRepository")
 */
class Contract extends BaseProject
{
    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE_CONTRACT;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        $project = $this->getProject();

        if ($project) {
            return $project->getClient();
        }
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        /** @var Project $parent */
        $parent = $this->getParent();

        return $parent;
    }

    /**
     * @param Project $project
     *
     * @return Project
     */
    public function setProject(Project $project)
    {
        return $this->setParent($project);
    }
}