<?php

namespace SmartProject\TimesheetBundle\Form;

use SmartProject\TimesheetBundle\Entity\Task\BaseProjectInterface;
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
     * @var BaseProjectInterface|null
     */
    private $project;

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
     * @param BaseProjectInterface $project
     */
    public function setProject($project = null)
    {
        $this->project = $project;
    }

    /**
     * @return BaseProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }
}
