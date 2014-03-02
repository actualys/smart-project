<?php

namespace SmartProject\TimesheetBundle\Controller;

use SmartProject\TimesheetBundle\Entity\Task;
use SmartProject\TimesheetBundle\Entity\Timesheet;
use SmartProject\TimesheetBundle\Entity\TimesheetRepository;
use SmartProject\TimesheetBundle\Entity\Tracking;
use SmartProject\TimesheetBundle\Entity\TrackingRepository;
use SmartProject\TimesheetBundle\Form\TaskQuickModel;
use SmartProject\TimesheetBundle\Form\TaskQuickType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
            $task->setDescription($form_data->getDescription());
            $task->setTags($form_data->getTags());
            $task->setClient($form_data->getClient());
            $task->setProject($form_data->getProject());
            $task->setContract($form_data->getContract());

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
            $content    = $this->renderView('SmartProjectTimesheetBundle:TaskQuick:new_form.html.twig', $parameters);
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
        $form = $this->createForm(
            new TaskQuickType(),
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

        /** @var Tracking $entity */
        $entity = $em->getRepository('SmartProjectTimesheetBundle:Tracking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tracking entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $editForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Task entity.
     *
     * @param Tracking $tracking The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Tracking $tracking)
    {
        $entity = new TaskQuickModel();
        $entity->setId($tracking->getId());
        $entity->setDate($tracking->getDate());
        $entity->setDuration($tracking->getDuration());
        $entity->setDescription($tracking->getTask()->getDescription());
        $entity->setTags($tracking->getTask()->getTags());
        $entity->setClient($tracking->getTask()->getClient());
        $entity->setProject($tracking->getTask()->getProject());
        $entity->setContract($tracking->getTask()->getContract());

        $options = array(
            'action' => $this->generateUrl('task_quick_update', array('id' => $entity->getId())),
            'method' => 'POST',
        );

        if ($entity->getClient()) {
            $options['show_project'] = true;
        }

        if ($entity->getProject()) {
            $options['show_contract'] = true;
        }

        $form = $this->createForm(
            new TaskQuickType('edit'),
            $entity,
            $options
        );

        return $form;
    }

    /**
     * Edits an existing Task entity.
     *
     * @Route("/{id}/edit", name="task_quick_update")
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $code = 202;

        /** @var Tracking $tracking */
        $tracking = $em->getRepository('SmartProjectTimesheetBundle:Tracking')->find($id);

        if (!$tracking) {
            throw $this->createNotFoundException('Unable to find Tracking entity.');
        }

        $form = $this->createEditForm($tracking);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $form_data = $form->getData();

            $task = $tracking->getTask();
            $task->setDescription($form_data->getDescription());
            $task->setTags($form_data->getTags());
            $task->setClient($form_data->getClient());
            $task->setProject($form_data->getProject());
            $task->setContract($form_data->getContract());

            $tracking->setDuration($form_data->getDuration());

            $em->flush();
        } else {
            $code = 400;
        }

        if ($request->isXmlHttpRequest()) {
            $parameters = array(
                'form' => $form->createView(),
            );
            $content    = $this->renderView('SmartProjectTimesheetBundle:TaskQuick:edit_form.html.twig', $parameters);
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

                $data['action'] = 'reload';//'redirect';
                $data['url']    = $url;
            }
            $response = new JsonResponse($data, $code);

            return $response;
        } else {
            return $this->redirect($this->generateUrl('timeline'));
        }
    }

    /**
     * Deletes a Task entity.
     *
     * @Route("/delete/{id}", name="task_quick_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $em       = $this->getDoctrine()->getManager();
        $code     = 202;
        $duration = 0;
        $total    = '';

        if ($request->isXmlHttpRequest()) {

            /** @var Tracking $tracking */
            $tracking = $em->getRepository('SmartProjectTimesheetBundle:Tracking')->find($id);
            $date     = $tracking->getDate();

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

            /** @var TrackingRepository $repositoryTracking */
            $repositoryTracking = $em->getRepository('SmartProjectTimesheetBundle:Tracking');
            $duration           = $repositoryTracking->getTotalDurationForDate($this->getUser(), $date);
            $total              = $this->formatDuration($duration, ' h ', true);
        }

        return new JsonResponse(
            array(
                'content'  => '',
                'action'   => '',
                'message'  => '',
                'duration' => $duration,
                'total'    => $total
            ),
            $code
        );
    }

    /**
     * @param mixed  $duration
     * @param string $separator
     * @param bool   $trim
     *
     * @return string
     */
    protected function formatDuration($duration, $separator = ':', $trim = false)
    {
        if (!is_numeric($duration)) {
            return $duration;
        }

        $int = floor($duration);

        if ($trim && $int == $duration) {
            return $int . $separator;
        } else {
            return $int . $separator . str_pad(floor(($duration - $int) * 60), 2, '0', STR_PAD_LEFT);
        }
    }
}
