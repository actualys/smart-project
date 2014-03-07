<?php

namespace SmartProject\ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use SmartProject\ProjectBundle\Entity\Project;

/**
 * Class RedmineController
 *
 * @package SmartProject\ProjectBundle\Controller
 */
class RedmineController extends Controller
{
    /**
     * @Template()
     */
    public function projectListAction()
    {
        $em       = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('SmartProjectProjectBundle:Redmine\Project')->findAll();
        $redmine  = $this->container->getParameter('redmine');

        return array(
            'redmine'  => $redmine,
            'entities' => $entities,
        );
    }
}
