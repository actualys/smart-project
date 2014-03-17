<?php

namespace SmartProject\TimesheetBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Gedmo\Loggable
 * @ORM\Table(name="timesheet_task",
 *            indexes={
 *            })
 * @ORM\Entity(repositoryClass="SmartProject\TimesheetBundle\Entity\TaskRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discriminator", type="string")
 * @ORM\DiscriminatorMap({
 *              "project" = "\SmartProject\TimesheetBundle\Entity\Task\TaskProject",
 *              "calendar" = "\SmartProject\TimesheetBundle\Entity\Task\TaskCalendar"
 *          })
 */
abstract class Task
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
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $tags;

    /**
     * @var Timesheet
     *
     * @ORM\ManyToOne(targetEntity="Timesheet", inversedBy="tasks", cascade={"persist"})
     * @ORM\JoinColumn(name="timesheet_id", referencedColumnName="id")
     */
    private $timesheet;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Tracking", mappedBy="task", cascade={"persist"})
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
        $this->trackings = new ArrayCollection();
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
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
    public function setTimesheet(Timesheet $timesheet = null)
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
     * Add trackings
     *
     * @param \SmartProject\TimesheetBundle\Entity\Tracking $trackings
     * @return Task
     */
    public function addTracking(Tracking $trackings)
    {
        $this->trackings[] = $trackings;
    
        return $this;
    }

    /**
     * Remove trackings
     *
     * @param \SmartProject\TimesheetBundle\Entity\Tracking $trackings
     */
    public function removeTracking(Tracking $trackings)
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