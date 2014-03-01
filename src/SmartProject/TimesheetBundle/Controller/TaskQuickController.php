<?php

namespace SmartProject\TimesheetBundle\Controller;

use SmartProject\ProjectBundle\Entity\ClientRepository;
use SmartProject\TimesheetBundle\Entity\Task;
use SmartProject\TimesheetBundle\Entity\Timesheet;
use SmartProject\TimesheetBundle\Entity\TimesheetRepository;
use SmartProject\TimesheetBundle\Entity\Tracking;
use SmartProject\TimesheetBundle\Form\TaskQuickModel;
use SmartProject\TimesheetBundle\Form\TaskQuickType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Task Quick controller.
 *
 * @Route("/task-quick")
 */
class TaskQuickController extends Controller
{
    /**
     * Creates a new Task entity.
     *
     * @Route("/", name="task_quick_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $form_data = new TaskQuickModel();
        $form      = $this->createCreateForm($form_data);

        $form->handleRequest($request);
        $url = $form_data->getUrl();

        if ($form->isValid()) {
            $user = $this->getUser();
            $date = $form_data->getDate();

            /** @var TimesheetRepository $timesheetRepository */
            $timesheetRepository = $this->getDoctrine()->getRepository('SmartProjectTimesheetBundle:Timesheet');
            /** @var Timesheet $timesheet */
            $timesheet = $timesheetRepository->findByUser($user, $date);

            if (null === $timesheet) {
                $timesheet = $timesheetRepository->createForUser($user, $date);
            }

            $task = new Task();
            $task->setTimesheet($timesheet);
            $task->setName($form_data->getName());
            $task->setTags($form_data->getTags());

            $tracking = new Tracking();
            $tracking->setTask($task);
            $tracking->setDate($date);
            $tracking->setDuration($form_data->getDuration());
            $tracking->setStatus(Tracking::STATUS_NEW);

            $em = $this->getDoctrine()->getManager();
            $em->persist($timesheet);
            $em->persist($task);
            $em->persist($tracking);
            $em->flush();

            $code      = 201;
            $form_data = new TaskQuickModel();
            $form      = $this->createCreateForm($form_data);

            $this->get('session')->getFlashBag()->add('success', 'Task correctly created: #' . $task->getId());
        } else {
            $code = 400;
        }

        if ($request->isXmlHttpRequest()) {
            $parameters = array(
                'form' => $form->createView(),
            );
            $content    = $this->renderView('SmartProjectTimesheetBundle:TaskQuick:form.html.twig', $parameters);
            $data       = array(
                'content' => $content,
                'action'  => '',
                'url'     => '',
                'message' => '',
            );
            if ($code < 400) {
                $url = $this->generateUrl(
                    'timeline_mode',
                    array('mode' => 'auto', 'date' => $tracking->getDate()->format('Y-m-d'))
                );

                $data['action'] = 'redirect';
                $data['url']    = $url;
            }
            $response = new JsonResponse($data, $code);

            return $response;
        } else {
            return $this->redirect($url);
        }
    }

    /**
     * Creates a form to create a Task entity.
     *
     * @param TaskQuickModel $form_data The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(TaskQuickModel $form_data)
    {
        /** @var ClientRepository $repository */
        $repository   = $this->getDoctrine()->getRepository('SmartProjectProjectBundle:Client');
        $queryBuilder = $repository->createQueryBuilder('cl')
          ->select('cl, pr, co')
          ->leftJoin('cl.projects', 'pr')
          ->leftJoin('pr.contracts', 'co')
          ->orderBy('cl.name', 'asc')
          ->addOrderBy('pr.name', 'asc')
          ->addOrderBy('co.name', 'asc');
        $clients      = $queryBuilder->getQuery()->execute();

        $tasks = array();

        foreach ($clients as $clientId => $client) {
            $tasks[$clientId] = $client->getName();

            foreach ($client->getProjects() as $projectId => $project) {
                $tasks[$clientId . ':' . $projectId] = $project->getName();

                foreach ($project->getContracts() as $contractId => $contract) {
                    $tasks[$clientId . ':' . $projectId . ':' . $contractId] = $contract->getName();
                }
            }
        }

//          ->getRepository('SmartProjectProjectBundle:Client')
//          ->findBy(array(), array('name' => 'asc'));
//
//        foreach ($clients )

        $form_type = new TaskQuickType();
        $form_type->setTasks($tasks);

        $form = $this->createForm(
            $form_type,
            $form_data,
            array(
                'action' => $this->generateUrl('task_quick_create'),
                'method' => 'POST',
            )
        );

        $form->add(
            'submit',
            'submit',
            array(
                'label' => 'Create',
                'attr'  => array('class' => 'btn-primary btn-block form-control')
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Task entity.
     *
     * @Route("/new", name="task_quick_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request, $url = null, $date = null)
    {
        if (null === $url) {
            $url = $request->getRequestUri();
        }

        $form_data = new TaskQuickModel();
        if (null !== $date) {
            $date = new \DateTime($date);
            $form_data->setDate($date);
        }
        $form_data->setUrl($url);

        $form = $this->createCreateForm($form_data);

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_quick_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectTimesheetBundle:TaskQuick')->find($id);

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
     * @param TaskQuickModel $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(TaskQuickModel $entity)
    {
        $form = $this->createForm(
            new TaskQuickType(),
            $entity,
            array(
                'action' => $this->generateUrl('task_quick_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}", name="task_quick_update")
     * @Method("PUT")
     * @Template("SmartProjectTimesheetBundle:TaskQuick:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectTimesheetBundle:TaskQuick')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('task_quick_edit', array('id' => $id)));
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
     * @Route("/delete/{id}", name="task_quick_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
//        $form = $this->createDeleteForm($id);
//        $form->handleRequest($request);

        $code = 202;

        if ($request->isXmlHttpRequest()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Tracking $tracking */
            $tracking = $em->getRepository('SmartProjectTimesheetBundle:Tracking')->find($id);

            if (!$tracking || $tracking->getTask()->getTimesheet()->getUser() != $this->getUser()) {
                $code = 400;
            } else {
                $task = $tracking->getTask();

                if ($task->getTrackings()->contains($tracking) && $task->getTrackings()->count() <= 1) {
                    $em->remove($task);
                }
                $em->remove($tracking);

                $em->flush();
            }
        }

        $total = '55 h 0';

        return new JsonResponse(array('content' => '', 'action' => '', 'message' => '', 'total' => $total), $code);
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
          ->setAction($this->generateUrl('task_quick_delete', array('id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm();
    }
}
