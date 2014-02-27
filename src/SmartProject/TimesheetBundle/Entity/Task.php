<?php

namespace SmartProject\TimesheetBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @ORM\Table(name="timesheet_task")
 * @ORM\Entity(repositoryClass="SmartProject\TimesheetBundle\Entity\TaskRepository")
 */
class Task
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=true)
     */
    private $tags;

    /**
     * @var Timesheet
     *
     * @ORM\ManyToOne(targetEntity="Timesheet", inversedBy="tasks")
     * @ORM\JoinColumn(name="timesheet_id", referencedColumnName="id")
     */
    private $timesheet;

    /**
     * @var ClientInterface
     *
     * @ORM\ManyToOne(targetEntity="ClientInterface")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=true)
     */
    private $client;

    /**
     * @var ProjectInterface
     *
     * @ORM\ManyToOne(targetEntity="ProjectInterface")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=true)
     */
    private $project;

    /**
     * @var ContractInterface
     *
     * @ORM\ManyToOne(targetEntity="ContractInterface")
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id", nullable=true)
     */
    private $contract;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Tracking", mappedBy="task")
     */
    private $trackings;

    /**
     * @var \DateTime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime $deletedAt
     *
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    private $deletedAt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->trackings = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set tags
     *
     * @param string $tags
     * @return Task
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    
        return $this;
    }

    /**
     * Get tags
     *
     * @return string 
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Task
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Task
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     * @return Task
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    
        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime 
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set timesheet
     *
     * @param \SmartProject\TimesheetBundle\Entity\Timesheet $timesheet
     * @return Task
     */
    public function setTimesheet(\SmartProject\TimesheetBundle\Entity\Timesheet $timesheet = null)
    {
        $this->timesheet = $timesheet;
    
        return $this;
    }

    /**
     * Get timesheet
     *
     * @return \SmartProject\TimesheetBundle\Entity\Timesheet 
     */
    public function getTimesheet()
    {
        return $this->timesheet;
    }

    /**
     * Set client
     *
     * @param \SmartProject\TimesheetBundle\Entity\ClientInterface $client
     * @return Task
     */
    public function setClient(\SmartProject\TimesheetBundle\Entity\ClientInterface $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \SmartProject\TimesheetBundle\Entity\ClientInterface 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set project
     *
     * @param \SmartProject\TimesheetBundle\Entity\ProjectInterface $project
     * @return Task
     */
    public function setProject(\SmartProject\TimesheetBundle\Entity\ProjectInterface $project = null)
    {
        $this->project = $project;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \SmartProject\TimesheetBundle\Entity\ProjectInterface 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set contract
     *
     * @param \SmartProject\TimesheetBundle\Entity\ContractInterface $contract
     * @return Task
     */
    public function setContract(\SmartProject\TimesheetBundle\Entity\ContractInterface $contract = null)
    {
        $this->contract = $contract;
    
        return $this;
    }

    /**
     * Get contract
     *
     * @return \SmartProject\TimesheetBundle\Entity\ContractInterface 
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Add trackings
     *
     * @param \SmartProject\TimesheetBundle\Entity\Tracking $trackings
     * @return Task
     */
    public function addTracking(\SmartProject\TimesheetBundle\Entity\Tracking $trackings)
    {
        $this->trackings[] = $trackings;
    
        return $this;
    }

    /**
     * Remove trackings
     *
     * @param \SmartProject\TimesheetBundle\Entity\Tracking $trackings
     */
    public function removeTracking(\SmartProject\TimesheetBundle\Entity\Tracking $trackings)
    {
        $this->trackings->removeElement($trackings);
    }

    /**
     * Get trackings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTrackings()
    {
        return $this->trackings;
    }
}