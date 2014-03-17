<?php

namespace SmartProject\ProjectBundle\Controller;

use SmartProject\FrontBundle\SmartProjectFrontBundle;
use SmartProject\ProjectBundle\Entity\AbstractEntity;
use SmartProject\ProjectBundle\Entity\Client;
use SmartProject\ProjectBundle\Entity\ClientRepository;
use SmartProject\ProjectBundle\Entity\ProjectRepository;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SmartProject\ProjectBundle\Entity\Project;
use SmartProject\ProjectBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/project")
 */
class ProjectController extends Controller
{
    /**
     * Lists all Project entities.
     *
     * @Route("/synchronize", name="project_synchronize")
     * @Method("GET")
     */
    public function synchronizeAction()
    {
        $this->get('smart_project_project.redmine')->synchronize();
        $this->get('session')->getFlashBag()->add('success', 'Project Redmine : synchronized');

        $url = $this->generateUrl('project');

        return $this->redirect($url, 302);
    }

    /**
     * @Route("/{slug}/create-client", name="project_create_client")
     * @Method("GET")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     */
    public function createClient(Project $project)
    {
        $em = $this->getDoctrine()->getManager();

        $client = new Client();
        $client->setName($project->getName());
        $project->setClient($client);

        $em->persist($client);
        $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
        $em->flush();

        $url = $this->generateUrl('project');

        return $this->redirect($url, 302);
    }

    /**
     * Get tags already selected.
     *
     * @Route("/tags", name="project_tags")
     * @Method("GET")
     */
    public function getTagsAction(Request $request)
    {
//        $query    = $request->get('q');
        $callback = $request->get('callback');

        $words = $tags = array();

        $words[] = 'CIVA';
        $words[] = 'DR';
        $words[] = 'Loire';
        $words[] = 'Passion Céréales';
        $words[] = 'La Poste';
        $words[] = 'Presse Poste';
        $words[] = 'TMA';

        foreach ($words as $word) {
            $tags[] = array(
                'id'   => $word,
                'text' => $word,
            );
        }

        $response = new JsonResponse(array('total' => count($tags), 'results' => $tags));
        $response->setCallback($callback);

        return $response;
    }

    /**
     * Lists all Project entities.
     *
     * @Route("/", name="project")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var ClientRepository $repoClient */
        $repoClient = $em->getRepository('SmartProjectProjectBundle:Client');
        $projects   = $repoClient->childrenQueryBuilder()
          ->select('node, project')
          ->from('SmartProject\ProjectBundle\Entity\Project', 'project')
          ->andWhere('node.id = project.root')
          ->orderBy('project.root', 'asc')
          ->addOrderBy('project.lft', 'asc')
          ->getQuery()
          ->execute();

        /** @var ProjectRepository $repoProject */
        $repoProject           = $em->getRepository('SmartProjectProjectBundle:Project');
        $projects_not_affected = $repoProject->childrenQueryBuilder()
          ->getQuery()
          ->execute();

        $redmine = $this->container->getParameter('redmine');

        return array(
            'projects'              => $projects,
            'projects_not_affected' => $projects_not_affected,
            'redmine'               => $redmine,
        );
    }

    /**
     * Creates a new Project entity.
     *
     * @Route("/", name="project_create")
     * @Method("POST")
     * @Template("SmartProjectProjectBundle:Project:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $project = new Project();
        $form    = $this->createCreateForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Project created');

            return $this->redirect($this->generateUrl('project_show', array('slug' => $project->getSlug())));
        } else {
            var_dump($form->getErrorsAsString());
        }

        return array(
            'client'  => $project->getClient(),
            'project' => $project,
            'form'    => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Project entity.
     *
     * @param Project $project The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Project $project)
    {
        $form = $this->createForm(
            new ProjectType(),
            $project,
            array(
                'action'  => $this->generateUrl('project_create'),
                'method'  => 'POST',
                'project' => $project,
            )
        );

        return $form;
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/new", name="project_new_empty")
     * @Method("GET")
     * @Template("SmartProjectProjectBundle:Project:new.html.twig")
     */
    public function newEmptyAction()
    {
        $project = new Project();
        $form    = $this->createCreateForm($project);

        return array(
            'client'  => null,
            'project' => $project,
            'form'    => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Project entity.
     *
     * @Route("/{slug}/new", name="project_new")
     * @Method("GET")
     * @ParamConverter("client", class="SmartProjectProjectBundle:Client")
     * @Template()
     */
    public function newAction(Client $client)
    {
        $project = new Project();
        $project->setClient($client);
        $form = $this->createCreateForm($project);

        return array(
            'client'  => $client,
            'project' => $project,
            'form'    => $form->createView(),
        );
    }

    /**
     * Finds and displays a Project entity.
     *
     * @Route("/{slug}", name="project_show")
     * @Method("GET")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     * @Template()
     */
    public function showAction(Project $project)
    {
        $deleteForm = $this->createDeleteForm($project);
        $redmine    = $this->container->getParameter('redmine');

        return array(
            'project'     => $project,
            'redmine'     => $redmine,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Project entity.
     *
     * @Route("/{slug}/edit", name="project_edit")
     * @Method("GET")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     * @Template()
     */
    public function editAction(Project $project)
    {
        $editForm   = $this->createEditForm($project);
        $deleteForm = $this->createDeleteForm($project);
        $redmine    = $this->container->getParameter('redmine');

        return array(
            'project'     => $project,
            'redmine'     => $redmine,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Project entity.
     *
     * @param Project $project The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Project $project)
    {
        $form = $this->createForm(
            new ProjectType(),
            $project,
            array(
                'action'  => $this->generateUrl('project_update', array('slug' => $project->getSlug())),
                'method'  => 'PUT',
                'project' => $project,
            )
        );

        return $form;
    }

    /**
     * Edits an existing Project entity.
     *
     * @Route("/{slug}", name="project_update")
     * @Method("PUT")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     * @Template("SmartProjectProjectBundle:Project:edit.html.twig")
     */
    public function updateAction(Request $request, Project $project)
    {
        $editForm = $this->createEditForm($project);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getFilters()->disable(SmartProjectFrontBundle::FILTER_SOFTDELETE);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Project updated');

            return $this->redirect($this->generateUrl('project_show', array('slug' => $project->getSlug())));
        }

        $deleteForm = $this->createDeleteForm($project);

        return array(
            'project'     => $project,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Project entity.
     *
     * @Route("/{slug}", name="project_delete")
     * @ParamConverter("project", class="SmartProjectProjectBundle:Project")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Project $project)
    {
        $form = $this->createDeleteForm($project);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($project);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Project deleted');
        }

        return $this->redirect($this->generateUrl('project'));
    }

    /**
     * Creates a form to delete a Project entity by id.
     *
     * @param Project $project The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Project $project)
    {
        $options = array(
            'render_fieldset' => false,
            'show_legend'     => false,
        );

        /** @var FormBuilder $formBuilder */
        $formBuilder = $this->createFormBuilder(null, $options)
          ->setAction($this->generateUrl('project_delete', array('slug' => $project->getSlug())))
          ->setMethod('DELETE');

        return $formBuilder->getForm();
    }
}
