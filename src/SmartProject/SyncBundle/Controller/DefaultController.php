<?php

namespace SmartProject\SyncBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SmartProjectSyncBundle:Default:index.html.twig', array('name' => $name));
    }
}
