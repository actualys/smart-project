<?php

namespace SmartProject\SyncBundle\Controller\Provider;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartProject\SyncBundle\Entity\Provider\ProviderRedmine;
use SmartProject\SyncBundle\Form\Provider\ProviderRedmineType;

/**
 * Provider\ProviderRedmine controller.
 *
 * @Route("/provider/redmine")
 */
class ProviderRedmineController extends Controller
{
    /**
     * Lists all Provider\ProviderRedmine entities.
     *
     * @Route("/", name="provider_redmine")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SmartProjectSyncBundle:Provider\ProviderRedmine')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Provider\ProviderRedmine entity.
     *
     * @Route("/", name="provider_redmine_create")
     * @Method("POST")
     * @Template("SmartProjectSyncBundle:Provider\ProviderRedmine:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ProviderRedmine();
        $form   = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('provider_redmine_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Provider\ProviderRedmine entity.
     *
     * @param ProviderRedmine $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ProviderRedmine $entity)
    {
        $form = $this->createForm(
            new ProviderRedmineType(),
            $entity,
            array(
                'action' => $this->generateUrl('provider_redmine_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Provider\ProviderRedmine entity.
     *
     * @Route("/new", name="provider_redmine_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new ProviderRedmine();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Provider\ProviderRedmine entity.
     *
     * @Route("/{id}", name="provider_redmine_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectSyncBundle:Provider\ProviderRedmine')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider\ProviderRedmine entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Provider\ProviderRedmine entity.
     *
     * @Route("/{id}/edit", name="provider_redmine_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectSyncBundle:Provider\ProviderRedmine')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider\ProviderRedmine entity.');
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
     * Creates a form to edit a Provider\ProviderRedmine entity.
     *
     * @param ProviderRedmine $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ProviderRedmine $entity)
    {
        $form = $this->createForm(
            new ProviderRedmineType(),
            $entity,
            array(
                'action' => $this->generateUrl('provider_redmine_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Provider\ProviderRedmine entity.
     *
     * @Route("/{id}", name="provider_redmine_update")
     * @Method("PUT")
     * @Template("SmartProjectSyncBundle:Provider\ProviderRedmine:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectSyncBundle:Provider\ProviderRedmine')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Provider\ProviderRedmine entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('provider_redmine_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Provider\ProviderRedmine entity.
     *
     * @Route("/{id}", name="provider_redmine_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SmartProjectSyncBundle:Provider\ProviderRedmine')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Provider\ProviderRedmine entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('provider_redmine'));
    }

    /**
     * Creates a form to delete a Provider\ProviderRedmine entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        /** @var FormBuilder $formBuilder */
        $formBuilder = $this->createFormBuilder();

        return $formBuilder->setAction($this->generateUrl('provider_redmine_delete', array('id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm();
    }
}
