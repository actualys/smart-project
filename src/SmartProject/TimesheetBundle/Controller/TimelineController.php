<?php

namespace SmartProject\TimesheetBundle\Controller;

use Doctrine\ORM\EntityManager;
use SmartProject\TimesheetBundle\Entity\Timesheet;
use SmartProject\TimesheetBundle\Entity\TimesheetRepository;
use SmartProject\TimesheetBundle\Entity\Tracking;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartProject\TimesheetBundle\Entity\Task;
use SmartProject\TimesheetBundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Timeline controller.
 *
 * @Route("/timeline")
 */
class TimelineController extends Controller
{
    const COOKIE_MODE = 'timeline_mode';

    /**
     * Lists all Task entities.
     *
     * @Route("/", name="timeline")
     * @Route("/{mode}", name="timeline_today")
     * @Route("/{mode}/{date}", name="timeline_mode")
     * @Method("GET")
     */
    public function indexAction(Request $request, $mode = null, $date = null)
    {
        if (null === $mode || 'auto' === $mode) {
            $mode = $request->cookies->get(self::COOKIE_MODE, 'day');
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        if (null !== $date) {
            $date = new \DateTime($date);
        } else {
            $date = new \DateTime('now');
        }

        $user = $this->getUser();

        /** @var TimesheetRepository $timesheetRepository */
        $timesheetRepository = $em->getRepository('SmartProjectTimesheetBundle:Timesheet');
        /** @var Timesheet $timesheet */
        $timesheet = $timesheetRepository->findByUser($user, $date);

        if (null === $timesheet) {
            $timesheet = $timesheetRepository->createForUser($user, $date);
        }

        $days = array();


        if ($mode == 'week') {
            /** @var \DateTime $dateStart */
            $dateStart = clone $timesheet->getDateStart();

            for ($i = 0; $i < 7; $i++) {
                $days[$dateStart->format('Y-m-d')] = array(
                    'trackings'   => array(),
                    'day_off'     => $i >= 5,
                    'fulfillment' => 0,
                );
                $dateStart->add(new \DateInterval('P1D'));
            }
        } else {
            $days[$date->format('Y-m-d')] = array(
                'trackings'   => array(),
                'day_off'     => ($date->format('w') == 6 || $date->format('w') == 0),
                'fulfillment' => 0,
            );
        }

        if ($timesheet->getId()) {
            $queryBuilder = $em->createQueryBuilder();
            $query        = $queryBuilder->select('tracking, task')
              ->from('SmartProjectTimesheetBundle:Tracking', 'tracking')
              ->join('tracking.task', 'task')
              ->where('task.timesheet = :timesheet')
              ->setParameter(':timesheet', $timesheet)
              ->orderBy('tracking.date', 'asc')
              ->addOrderBy('task.name', 'asc')
              ->addOrderBy('task.id', 'asc')
              ->getQuery();
            $entities     = $query->execute();
        } else {
            $entities = array();
        }

        /** @var Tracking $tracking */
        foreach ($entities as $tracking) {
            $date_formatted = $tracking->getDate()->format('Y-m-d');

            if ($mode != 'week' && $date_formatted != $date->format('Y-m-d')) {
                continue;
            }

            $days[$date_formatted]['trackings'][] = $tracking;
            $days[$date_formatted]['day_off']     = false;
            $days[$date_formatted]['fulfillment'] += $tracking->getDuration();
        }

        $parameters = array(
            'mode'         => $mode,
            'timesheet'    => $timesheet,
            'days'         => $days,
            'date'         => $date,
            'day_duration' => 7,
        );
        $content    = $this->renderView('SmartProjectTimesheetBundle:Timeline:index.html.twig', $parameters);
        $response   = new Response($content);
        $cookie     = new Cookie(self::COOKIE_MODE, $mode, 0, $request->getBaseUrl());
        $response->headers->setCookie($cookie);

        return $response;
    }

    /**
     * Creates a new Task entity.
     *
     * @Route("/", name="timeline_create")
     * @Method("POST")
     * @Template("SmartProjectTimesheetBundle:Timeline:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Task();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('task_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Task entity.
     *
     * @param Task $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Task $entity)
    {
        $form = $this->createForm(
            new TaskType(),
            $entity,
            array(
                'action' => $this->generateUrl('task_quick_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="timeline_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Task();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Task entity.
     *
     * @Route("/{id}", name="timeline_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectTimesheetBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="timeline_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectTimesheetBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $editForm   = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Task entity.
     *
     * @param Task $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Task $entity)
    {
        $form = $this->createForm(
            new TaskType(),
            $entity,
            array(
                'action' => $this->generateUrl('task_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}", name="timeline_update")
     * @Method("PUT")
     * @Template("SmartProjectTimesheetBundle:Timeline:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectTimesheetBundle:Task')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('task_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/{id}", name="timeline_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SmartProjectTimesheetBundle:Task')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Task entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }

    /**
     * Creates a form to delete a Task entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
          ->setAction($this->generateUrl('task_delete', array('id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm();
    }
}
