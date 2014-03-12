<?php

namespace SmartProject\TimesheetBundle\Entity\Task;

/**
 * Interface ProjectInterface
 *
 * @package SmartProject\TimesheetBundle\Entity\Task
 */
interface ProjectInterface
{
    /**
     * @return ClientInterface
     */
    public function getClient();
}