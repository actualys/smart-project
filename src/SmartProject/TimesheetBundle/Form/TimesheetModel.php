<?php

namespace SmartProject\TimesheetBundle\Form;

use Doctrine\Common\Collections\ArrayCollection;
use SmartProject\TimesheetBundle\Entity\Task\ClientInterface;
use SmartProject\TimesheetBundle\Entity\Task\ContractInterface;
use SmartProject\TimesheetBundle\Entity\Task\ProjectInterface;
use SmartProject\TimesheetBundle\Entity\Timesheet;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TimesheetModel
 *
 * @package SmartProject\TimesheetBundle\Form
 */
class TimesheetModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var Timesheet
     */
    private $timesheet;

    /**
     * @var ArrayCollection
     */
    private $tasks;

    /**
     *
     */
    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param Timesheet $timesheet
     */
    public function setTimesheet(Timesheet $timesheet)
    {
        $this->timesheet = $timesheet;
    }

    /**
     * @return Timesheet
     */
    public function getTimesheet()
    {
        return $this->timesheet;
    }

    /**
     * Add tasks
     *
     * @param TimesheetTaskModel $tasks
     *
     * @return TimesheetModel
     */
    public function addTask(TimesheetTaskModel $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param TimesheetTaskModel $tasks
     */
    public function removeTask(TimesheetTaskModel $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
