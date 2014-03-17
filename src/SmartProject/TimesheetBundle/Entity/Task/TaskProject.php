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
 */
class TaskProject extends Task
{
    /**
     * @var BaseProjectInterface
     *
     * @ORM\ManyToOne(targetEntity="BaseProjectInterface")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $project;

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
