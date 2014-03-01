<?php

namespace SmartProject\TimesheetBundle\Form;

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
     * @var \DateTime
     * @Assert\NotBlank(message="Date must be specified")
     */
    private $date;

    /**
     * @var string
     */
    private $client;

    /**
     * @var string
     */
    private $project;

    /**
     * @var string
     */
    private $contract;

    /**
     * @var string
     * @Assert\NotBlank(message="Description must be specified")
     */
    private $name;

    /**
     * @var string
     */
    private $tags;

    /**
     * @var double
     * @Assert\NotBlank(message="duration must be specified")
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
     * @param string $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $contract
     */
    public function setContract($contract)
    {
        $this->contract = $contract;
    }

    /**
     * @return mixed
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }
}
