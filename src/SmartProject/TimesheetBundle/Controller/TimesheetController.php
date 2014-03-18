<?php

namespace SmartProject\TimesheetBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use SmartProject\TimesheetBundle\Entity\Timesheet;
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
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

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
        /** @var Timesheet $timesheet */
        $timesheet = $timesheetRepository->findByUser($user, $date, true);

        $form = $this->createCreateForm($timesheet, $date);

        return array(
            'date'      => $date,
            'timesheet' => $timesheet,
            'form'      => $form->createView(),
        );
    }

    /**
     * Lists all Task entities.
     *
     * @Route("/{date}", name="timesheet_submit")
     * @Method("POST")
     * @Template("SmartProjectTimesheetBundle:Timesheet:index.html.twig")
     */
    public function updateAction(Request $request, $date)
    {
        $date = new \DateTime($date);

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        /** @var UserInterface $user */
        $user = $this->getUser();
        /** @var TimesheetRepository $timesheetRepository */
        $timesheetRepository = $em->getRepository('SmartProjectTimesheetBundle:Timesheet');
        /** @var Timesheet $timesheet */
        $timesheet = $timesheetRepository->findByUser($user, $date, true);

        $form = $this->createUpdateForm($timesheet, $date);
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var TimesheetModel $data */
            $data = $form->getData();

            try {
                $em->beginTransaction();

                $tasksToKeep = new ArrayCollection();

                /** @var TimesheetTaskModel $taskForm */
                foreach ($data->getTasks() as $taskForm) {
                    $found = false;

                    // Existing task ?
                    if ($taskForm->getId()) {
                        /** @var Task\TaskProject $task */
                        foreach ($timesheet->getTasks() as $task) {
                            // Known task ?
                            if ($task->getId() == $taskForm->getId()) {
                                $found = true;
                                break;
                            }
                        }
                    }

                    // New task
                    if (!$found) {
                        $task = new Task\TaskProject();
                        $timesheet->addTask($task);
                        $task->setTimesheet($timesheet);
                    }

                    $task->setProject($taskForm->getProject());
                    $task->setDescription($taskForm->getDescription());

                    $done = array(
                        1 => false,
                        2 => false,
                        3 => false,
                        4 => false,
                        5 => false,
                        6 => false,
                        7 => false,
                    );

                    /** Support for update and delete */
                    /** @var Tracking $tracking */
                    foreach ($task->getTrackings() as $tracking) {
                        $day      = $tracking->getDate()->format('N');
                        $duration = $taskForm->getDuration($day);

                        if (floatval($duration) == 0) {
                            $task->getTrackings()->removeElement($tracking);
                            $em->remove($tracking);
                        } else {
                            $tracking->setDuration($duration);
                        }

                        $done[$day] = true;
                    }

                    // Necessary to store empty task ?
                    if (array_sum($done) == 0) {
                        $em->remove($task);
                        $timesheet->getTasks()->removeElement($task);
                    } else {
                        // Task marked to be kept
                        $tasksToKeep->add($task);

                        /** Support for create */
                        foreach ($done as $day => $already) {
                            if (!$already) {
                                $duration = $taskForm->getDuration($day);

                                if (floatval($duration) > 0) {
                                    $date = clone $timesheet->getDateStart();
                                    $date->add(new \DateInterval('P' . ($day - 1) . 'D'));

                                    $tracking = new Tracking();
                                    $tracking->setDate($date);
                                    $tracking->setDuration($duration);
                                    $task->addTracking($tracking);
                                    $tracking->setTask($task);
                                }
                            }
                        }
                    }

                }

                // Remove old tasks
                foreach ($timesheet->getTasks() as $task) {
                    if (!$tasksToKeep->contains($task)) {
                        $timesheet->removeTask($task);
                        $em->remove($task);
                    }
                }

                $em->persist($timesheet);
                $em->flush();
                $em->commit();

            } catch (\Exception $e) {
                $em->rollback();

                throw $e;
            }

            return $this->redirect($this->generateUrl('timesheet', array('date' => $date->format('Y-m-d'))));
        } else {
            $form = $this->createCreateForm($timesheet, $date);
            $form->handleRequest($request);

            return array(
                'date'      => $date,
                'timesheet' => $timesheet,
                'form'      => $form->createView(),
            );
        }
    }

    /**
     * @param Timesheet $timesheet
     * @param \DateTime $date
     *
     * @return Form
     */
    private function createCreateForm(Timesheet $timesheet, \DateTime $date)
    {
        $formModel = new TimesheetModel();
        $formModel->setId($timesheet->getId());

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

        foreach ($tasks as $task) {
            $formModel->addTask($task);
        }

        $form = $this->createForm(
            new TimesheetType(),
            $formModel,
            array(
                'action' => $this->generateUrl('timesheet_submit', array('date' => $date->format('Y-m-d'))),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * @param Timesheet $timesheet
     * @param \DateTime $date
     *
     * @return Form
     */
    private function createUpdateForm(Timesheet $timesheet, \DateTime $date)
    {
        $formModel = new TimesheetModel();
        $formModel->setId($timesheet->getId());

        $form = $this->createForm(
            new TimesheetType(),
            $formModel,
            array(
                'action' => $this->generateUrl('timesheet_submit', array('date' => $date->format('Y-m-d'))),
                'method' => 'POST',
            )
        );

        return $form;
    }
}
