<?php

namespace SmartProject\ProjectBundle\Controller;

use SmartProject\FrontBundle\SmartProjectFrontBundle;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SmartProject\ProjectBundle\Entity\Client;
use SmartProject\ProjectBundle\Form\ClientType;

/**
 * Client controller.
 *
 * @Route("/client")
 */
class ClientController extends Controller
{
    /**
     * Creates a new Client entity.
     *
     * @Route("/", name="client_create")
     * @Method("POST")
     * @Template("SmartProjectProjectBundle:Client:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $client = new Client();
        $form   = $this->createCreateForm($client);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($client);

            $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
            $em->flush();

            return $this->redirect($this->generateUrl('client_show', array('slug' => $client->getSlug())));
        }

        return array(
            'client' => $client,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Client entity.
     *
     * @param Client $client The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Client $client)
    {
        $form = $this->createForm(
            new ClientType(),
            $client,
            array(
                'action' => $this->generateUrl('client_create'),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Client entity.
     *
     * @Route("/new", name="client_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $client = new Client();
        $form   = $this->createCreateForm($client);

        return array(
            'client' => $client,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Client entity.
     *
     * @Route("/{slug}", name="client_show")
     * @Method("GET")
     * @ParamConverter("client", class="SmartProjectProjectBundle:Client")
     * @Template()
     */
    public function showAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client);
        $timesheet  = array();

        return array(
            'client'      => $client,
            'timesheet'   => $timesheet,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Client entity.
     *
     * @Route("/{slug}/edit", name="client_edit")
     * @Method("GET")
     * @ParamConverter("client", class="SmartProjectProjectBundle:Client")
     * @Template()
     */
    public function editAction(Client $client)
    {
        $editForm   = $this->createEditForm($client);
        $deleteForm = $this->createDeleteForm($client);

        return array(
            'client'      => $client,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Client entity.
     *
     * @param Client $client The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Client $client)
    {
        $form = $this->createForm(
            new ClientType(),
            $client,
            array(
                'action' => $this->generateUrl('client_update', array('slug' => $client->getSlug())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Client entity.
     *
     * @Route("/{slug}", name="client_update")
     * @Method("PUT")
     * @ParamConverter("client", class="SmartProjectProjectBundle:Client")
     * @Template("SmartProjectProjectBundle:Client:edit.html.twig")
     */
    public function updateAction(Request $request, Client $client)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($client);
        $editForm   = $this->createEditForm($client);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
            $em->flush();

            return $this->redirect($this->generateUrl('client_show', array('slug' => $client->getSlug())));
        }

        return array(
            'client'      => $client,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Client entity.
     *
     * @Route("/{slug}", name="client_delete")
     * @ParamConverter("client", class="SmartProjectProjectBundle:Client")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Client deleted');
        }

        return $this->redirect($this->generateUrl('project'));
    }

    /**
     * Creates a form to delete a Client entity by id.
     *
     * @param Client $client The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        $options = array(
            'render_fieldset' => false,
            'show_legend'     => false,
        );

        /** @var FormBuilder $formBuilder */
        $formBuilder = $this->createFormBuilder(null, $options)
          ->setAction($this->generateUrl('client_delete', array('slug' => $client->getSlug())))
          ->setMethod('DELETE');

        return $formBuilder->getForm();
    }
}
