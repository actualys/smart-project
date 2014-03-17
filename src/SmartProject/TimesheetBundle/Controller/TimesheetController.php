<?php

namespace SmartProject\TimesheetBundle\Controller;

use Doctrine\ORM\EntityManager;
use SmartProject\TimesheetBundle\Entity\TimesheetRepository;
use SmartProject\TimesheetBundle\Entity\Tracking;
use SmartProject\TimesheetBundle\Entity\UserInterface;
use SmartProject\TimesheetBundle\Form\TimesheetModel;
use SmartProject\TimesheetBundle\Form\TimesheetTaskModel;
use SmartProject\TimesheetBundle\Form\TimesheetType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartProject\TimesheetBundle\Entity\Task;

/**
 * Timesheet controller.
 *
 * @Route("/timesheet")
 */
class TimesheetController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="timesheet_today")
     * @Route("/{date}", name="timesheet")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($date = null)
    {
        if (null !== $date) {
            $date = new \DateTime($date);
        } else {
            $date = new \DateTime('now');
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var UserInterface $user */
        $user = $this->getUser();
        /** @var TimesheetRepository $timesheetRepository */
        $timesheetRepository = $em->getRepository('SmartProjectTimesheetBundle:Timesheet');
        $timesheet           = $timesheetRepository->findByUser($user, $date, true);

        $formModel = new TimesheetModel();
        $formModel->setId($timesheet->getId());
        //$formModel->setTimesheet($timesheet);

        $tasks = array();

        /** @var Task $task */
        foreach ($timesheet->getTasks() as $task) {
            $taskModel = new TimesheetTaskModel();
            $taskModel->setId($task->getId());
            $taskModel->setDescription($task->getDescription());

            if ($task instanceof Task\TaskProject) {
                /** @var Task\TaskProject $task */
                $taskModel->setProject($task->getProject());
            }

            /** @var Tracking $tracking */
            foreach ($task->getTrackings() as $tracking) {
                $taskModel->setDuration(
                    $tracking->getDate()->format('N'),
                    $tracking->getDuration()
                );
            }

            $tasks[] = $taskModel;
        }

        usort($tasks, function (TimesheetTaskModel $a, TimesheetTaskModel $b) {
//                if ($a->getParentedName() && $b->getParentedName()) {
//                    return strnatcasecmp($a->getClient()->getName(), $b->getClient()->getName());
//                }
            });

        foreach ($tasks as $task) {
            $formModel->addTask($task);
        }

        $form = $this->createForm(
            new TimesheetType(),
            $formModel,
            array(
                'action' => $this->generateUrl('timesheet', array('date' => $date->format('Y-m-d'))),
                'method' => 'POST',
            )
        );

        return array(
            'date'      => $date,
            'timesheet' => null,
            'form'      => $form->createView(),
        );
    }
}
