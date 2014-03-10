<?php

namespace SmartProject\TimesheetBundle\Entity\Task;

use Doctrine\ORM\Mapping as ORM;
use SmartProject\TimesheetBundle\Entity\Task;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TaskCalendar
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="timesheet_task_calendar")
 * @ORM\Entity(repositoryClass="SmartProject\TimesheetBundle\Entity\Task\TaskCalendarRepository")
 */
class TaskCalendar extends Task
{
    // TODO
}
