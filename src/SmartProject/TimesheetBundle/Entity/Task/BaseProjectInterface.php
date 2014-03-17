<?php

namespace SmartProject\TimesheetBundle\Entity\Task;

/**
 * Interface BaseProjectInterface
 *
 * @package SmartProject\TimesheetBundle\Entity\Task
 */
interface BaseProjectInterface
{
    /**
     * @return BaseProjectInterface
     */
    public function getClient();
}