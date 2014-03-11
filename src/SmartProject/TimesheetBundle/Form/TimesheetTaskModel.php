<?php

namespace SmartProject\TimesheetBundle\Form;

use SmartProject\TimesheetBundle\Entity\Task\ClientInterface;
use SmartProject\TimesheetBundle\Entity\Task\ContractInterface;
use SmartProject\TimesheetBundle\Entity\Task\ProjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TimesheetTaskModel
 *
 * @package SmartProject\TimesheetBundle\Form
 */
class TimesheetTaskModel
{
    /**
     * @var TimesheetModel
     */
    private $timesheet;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var ClientInterface|null
     */
    private $client;

    /**
     * @var ProjectInterface|null
     */
    private $project;

    /**
     * @var ContractInterface|null
     */
    private $contract;

    /**
     * @var string
     * @Assert\NotBlank(message="Description must be specified")
     */
    private $description;

    /**
     * @var string
     */
    private $tags;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day1;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day2;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day3;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day4;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day5;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day6;

    /**
     * @var double
     * @Assert\Range(min=0, max=100, minMessage="Duration can't be lower than 0.1", maxMessage="Duration can't be greater than 100")
     */
    private $duration_day7;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @param null|\SmartProject\TimesheetBundle\Entity\Task\ClientInterface $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param null|\SmartProject\TimesheetBundle\Entity\Task\ContractInterface $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    /**
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\ContractInterface
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
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
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $duration_day1
     */
    public function setDurationDay1($duration_day1)
    {
        $this->duration_day1 = $duration_day1;
    }

    /**
     * @return mixed
     */
    public function getDurationDay1()
    {
        return $this->duration_day1;
    }

    /**
     * @param mixed $duration_day2
     */
    public function setDurationDay2($duration_day2)
    {
        $this->duration_day2 = $duration_day2;
    }

    /**
     * @return mixed
     */
    public function getDurationDay2()
    {
        return $this->duration_day2;
    }

    /**
     * @param mixed $duration_day3
     */
    public function setDurationDay3($duration_day3)
    {
        $this->duration_day3 = $duration_day3;
    }

    /**
     * @return mixed
     */
    public function getDurationDay3()
    {
        return $this->duration_day3;
    }

    /**
     * @param mixed $duration_day4
     */
    public function setDurationDay4($duration_day4)
    {
        $this->duration_day4 = $duration_day4;
    }

    /**
     * @return mixed
     */
    public function getDurationDay4()
    {
        return $this->duration_day4;
    }

    /**
     * @param float $duration_day5
     */
    public function setDurationDay5($duration_day5)
    {
        $this->duration_day5 = $duration_day5;
    }

    /**
     * @return float
     */
    public function getDurationDay5()
    {
        return $this->duration_day5;
    }

    /**
     * @param mixed $duration_day6
     */
    public function setDurationDay6($duration_day6)
    {
        $this->duration_day6 = $duration_day6;
    }

    /**
     * @return mixed
     */
    public function getDurationDay6()
    {
        return $this->duration_day6;
    }

    /**
     * @param float $duration_day7
     */
    public function setDurationDay7($duration_day7)
    {
        $this->duration_day7 = $duration_day7;
    }

    /**
     * @return float
     */
    public function getDurationDay7()
    {
        return $this->duration_day7;
    }

    /**
     * @param null|\SmartProject\TimesheetBundle\Entity\Task\ProjectInterface $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return null|\SmartProject\TimesheetBundle\Entity\Task\ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param string $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \SmartProject\TimesheetBundle\Form\TimesheetModel $timesheet
     */
    public function setTimesheet($timesheet)
    {
        $this->timesheet = $timesheet;
    }

    /**
     * @return \SmartProject\TimesheetBundle\Form\TimesheetModel
     */
    public function getTimesheet()
    {
        return $this->timesheet;
    }
}
