<?php

namespace SmartProject\TimesheetBundle\Form;

use SmartProject\TimesheetBundle\Entity\ClientInterface;
use SmartProject\TimesheetBundle\Entity\ContractInterface;
use SmartProject\TimesheetBundle\Entity\ProjectInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TaskQuickModel
 *
 * @package SmartProject\TimesheetBundle\Form
 */
class TaskQuickModel
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="Date must be specified")
     */
    private $date;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var ProjectInterface
     */
    private $project;

    /**
     * @var ContractInterface
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
     * @Assert\NotBlank(message="Duration must be specified")
     * @Assert\Range(
     *          min=0.1,
     *          max=100,
     *          minMessage="Duration can't be lower than 0.1",
     *          maxMessage="Duration can't be greater than 100")
     */
    private $duration;

    /**
     *
     */
    public function __construct()
    {
        $this->url  = '';
        $this->date = new \DateTime();
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
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
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
     * @param double $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return double
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param \SmartProject\TimesheetBundle\Entity\ClientInterface $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return \SmartProject\TimesheetBundle\Entity\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \SmartProject\TimesheetBundle\Entity\ContractInterface $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    /**
     * @return \SmartProject\TimesheetBundle\Entity\ContractInterface
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param \SmartProject\TimesheetBundle\Entity\ProjectInterface $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return \SmartProject\TimesheetBundle\Entity\ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }
}
