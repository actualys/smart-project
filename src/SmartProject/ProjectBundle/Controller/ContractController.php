<?php

namespace SmartProject\ProjectBundle\Controller;

use SmartProject\ProjectBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SmartProject\ProjectBundle\Entity\Contract;
use SmartProject\ProjectBundle\Form\ContractType;

/**
 * Contract controller.
 *
 * @Route("/contract")
 */
class ContractController extends Controller
{

    /**
     * Lists all Contract entities.
     *
     * @Route("/", name="contract")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SmartProjectProjectBundle:Contract')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Contract entity.
     *
     * @Route("/", name="contract_create")
     * @Route("/{project}", name="contract_create_project")
     * @Method("POST")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project", options={"id" = "project"})
     * @Template("SmartProjectProjectBundle:Contract:new.html.twig")
     */
    public function createAction(Request $request, Project $project)
    {
        $entity = new Contract();

        if ($project) {
            $entity->setProject($project);
        }

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('contract_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Contract entity.
     *
     * @param Contract $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contract $entity)
    {
        $form = $this->createForm(
            new ContractType(),
            $entity,
            array(
                'action' => $this->generateUrl('contract_create'),
                'method' => 'POST',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Contract entity.
     *
     * @Route("/new", name="contract_new")
     * @Route("/new/{project}", name="contract_new_project")
     * @Method("GET")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project", options={"id" = "project"})
     * @Template()
     */
    public function newAction(Request $request, Project $project)
    {
        $entity = new Contract();

        if ($project) {
            $entity->setProject($project);
        }

        $form = $this->createCreateForm($entity);

        return array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'form_action' => $this->generateUrl('contract_create_project', array('project' => $project->getId())),
        );
    }

    /**
     * Finds and displays a Contract entity.
     *
     * @Route("/{id}", name="contract_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectProjectBundle:Contract')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contract entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Contract entity.
     *
     * @Route("/{id}/edit", name="contract_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectProjectBundle:Contract')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contract entity.');
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
     * Creates a form to edit a Contract entity.
     *
     * @param Contract $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Contract $entity)
    {
        $form = $this->createForm(
            new ContractType(),
            $entity,
            array(
                'action' => $this->generateUrl('contract_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            )
        );

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Contract entity.
     *
     * @Route("/{id}", name="contract_update")
     * @Method("PUT")
     * @Template("SmartProjectProjectBundle:Contract:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SmartProjectProjectBundle:Contract')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Contract entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm   = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('contract_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Contract entity.
     *
     * @Route("/{id}", name="contract_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em     = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SmartProjectProjectBundle:Contract')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Contract entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contract'));
    }

    /**
     * Creates a form to delete a Contract entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
          ->setAction($this->generateUrl('contract_delete', array('id' => $id)))
          ->setMethod('DELETE')
          ->add('submit', 'submit', array('label' => 'Delete'))
          ->getForm();
    }
}
