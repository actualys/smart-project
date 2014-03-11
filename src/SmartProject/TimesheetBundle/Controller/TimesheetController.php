<?php

namespace SmartProject\TimesheetBundle\Controller;

use Doctrine\ORM\EntityManager;
use SmartProject\TimesheetBundle\Form\TimesheetModel;
use SmartProject\TimesheetBundle\Form\TimesheetTaskModel;
use SmartProject\TimesheetBundle\Form\TimesheetType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartProject\TimesheetBundle\Entity\Task;

/**
 * Timesheet controller.
 *
 * @Route("/full")
 */
class TimesheetController extends Controller
{
    /**
     * Lists all Task entities.
     *
     * @Route("/", name="timesheet")
     * @Route("/{date}", name="timesheet_today")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request, $date = null)
    {
        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if (null !== $date) {
            $date = new \DateTime($date);
        } else {
            $date = new \DateTime('now');
        }

        $formModel = new TimesheetModel();
        $taskModel = new TimesheetTaskModel();
        $taskModel->setDescription('description de la tache n°1');
        $taskModel->setDurationDay1(.5);
        $taskModel->setDurationDay2(1.5);
        $formModel->addTask($taskModel);
        $taskModel = new TimesheetTaskModel();
        $taskModel->setDescription('description de la tache n°2');
        $taskModel->setDurationDay1(1);
        $taskModel->setDurationDay7(2.5);
        $formModel->addTask($taskModel);

        $form      = $this->createForm(
            new TimesheetType(),
            $formModel,
            array(
                'action' => $this->generateUrl('timesheet_today', array('date' => $date->format('Y-m-d'))),
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
