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
     * @var ClientInterface
     *
     * @ORM\ManyToOne(targetEntity="ClientInterface")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $client;

    /**
     * @var ProjectInterface
     *
     * @ORM\ManyToOne(targetEntity="ProjectInterface")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $project;

    /**
     * @var ContractInterface
     *
     * @ORM\ManyToOne(targetEntity="ContractInterface")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id", nullable=true)
     * @Gedmo\Versioned
     */
    private $contract;

    /**
     * Set client
     *
     * @param \SmartProject\TimesheetBundle\Entity\Task\ClientInterface $client
     * @return Task
     */
    public function setClient($client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set project
     *
     * @param \SmartProject\TimesheetBundle\Entity\Task\ProjectInterface $project
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
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set contract
     *
     * @param \SmartProject\TimesheetBundle\Entity\Task\ContractInterface $contract
     * @return Task
     */
    public function setContract($contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\ContractInterface
     */
    public function getContract()
    {
        return $this->contract;
    }
}
