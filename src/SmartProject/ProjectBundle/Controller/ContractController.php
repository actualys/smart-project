<?php

namespace SmartProject\ProjectBundle\Controller;

use SmartProject\FrontBundle\SmartProjectFrontBundle;
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
     * Creates a new Contract entity.
     *
     * @Route("/new/project/{slug}", name="contract_create")
     * @Method("POST")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     * @Template("SmartProjectProjectBundle:Contract:new.html.twig")
     */
    public function createAction(Request $request, Project $project)
    {
        $contract = new Contract();
        $contract->setProject($project);
        $form = $this->createCreateForm($contract);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contract);
            $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
            $em->flush();

            return $this->redirect($this->generateUrl('contract_show', array('slug' => $contract->getSlug())));
        }

        return array(
            'contract' => $contract,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Contract entity.
     *
     * @param Contract $contract The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Contract $contract)
    {
        $form = $this->createForm(
            new ContractType(),
            $contract,
            array(
                'action' => $this->generateUrl('contract_create', array('slug' => $contract->getProject()->getSlug())),
                'method' => 'POST',
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Contract entity.
     *
     * @Route("/new/project/{slug}", name="contract_new")
     * @Method("GET")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     * @Template()
     */
    public function newAction(Project $project)
    {
        $contract = new Contract();
        $contract->setProject($project);
        $form    = $this->createCreateForm($contract);
        $redmine = $this->container->getParameter('redmine');

        return array(
            'contract'    => $contract,
            'project'     => $project,
            'redmine'     => $redmine,
            'form'        => $form->createView(),
            'form_action' => $this->generateUrl('contract_create', array('slug' => $project->getId())),
        );
    }

    /**
     * Finds and displays a Contract entity.
     *
     * @Route("/{slug}", name="contract_show")
     * @Method("GET")
     * @ParamConverter("contract", class="SmartProjectProjectBundle:Contract")
     * @Template()
     */
    public function showAction(Contract $contract)
    {
        $deleteForm = $this->createDeleteForm($contract);

        return array(
            'contract'    => $contract,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Contract entity.
     *
     * @Route("/{slug}/edit", name="contract_edit")
     * @Method("GET")
     * @Template()
     * @ParamConverter("contract", class="SmartProjectProjectBundle:Contract")
     */
    public function editAction(Contract $contract)
    {
        $editForm   = $this->createEditForm($contract);
        $deleteForm = $this->createDeleteForm($contract);

        return array(
            'contract'      => $contract,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Contract entity.
     *
     * @param Contract $contract The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Contract $contract)
    {
        $form = $this->createForm(
            new ContractType(),
            $contract,
            array(
                'action' => $this->generateUrl('contract_update', array('slug' => $contract->getSlug())),
                'method' => 'PUT',
            )
        );

        return $form;
    }

    /**
     * Edits an existing Contract entity.
     *
     * @Route("/{slug}", name="contract_update")
     * @Method("PUT")
     * @Template("SmartProjectProjectBundle:Contract:edit.html.twig")
     * @ParamConverter("contract", class="SmartProjectProjectBundle:Contract")
     */
    public function updateAction(Request $request, Contract $contract)
    {
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($contract);
        $editForm   = $this->createEditForm($contract);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
            $em->flush();

            return $this->redirect($this->generateUrl('contract_edit', array('slug' => $contract->getSlug())));
        }

        return array(
            'contract'    => $contract,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Contract entity.
     *
     * @Route("/{slug}", name="contract_delete")
     * @Method("DELETE")
     * @ParamConverter("contract", class="SmartProjectProjectBundle:Contract")
     */
    public function deleteAction(Request $request, Contract $contract)
    {
        $form = $this->createDeleteForm($contract);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contract);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('contract'));
    }

    /**
     * Creates a form to delete a Contract entity by id.
     *
     * @param Contract $contract The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Contract $contract)
    {
        return $this->createFormBuilder()
          ->setAction($this->generateUrl('contract_delete', array('slug' => $contract->getSlug())))
          ->setMethod('DELETE')
          ->getForm();
    }
}
