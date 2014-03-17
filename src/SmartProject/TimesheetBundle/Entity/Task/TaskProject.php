<?php

namespace SmartProject\TimesheetBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use SmartProject\TimesheetBundle\Entity\Task;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TaskProject
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="timesheet_task_project")
 * @ORM\Entity(repositoryClass="SmartProject\TimesheetBundle\Entity\Task\TaskProjectRepository")
 * @ORM\HasLifecycleCallbacks
 */
class TaskProject extends Task
{
    /**
     * @var BaseProjectInterface
     *
     * @ORM\ManyToOne(targetEntity="ProjectInterface")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
//        if ($this->contract) {
//            $this->client = null;
//            $this->project = null;
//        } elseif ($this->project) {
//            $this->client = null;
//        }
    }

    /**
     * @ORM\PostPersist
     */
    public function onPostPersist()
    {
//        if ($this->contract) {
//            $this->project = $this->contract->getProject();
//            $this->client = $this->project->getClient();
//        } elseif ($this->project) {
//            $this->client = $this->project->getClient();
//        }
    }

    /**
     * Set project
     *
     * @param BaseProjectInterface $project
     * @return Task
     */
    public function setProject($project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return null|BaseProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }
}
