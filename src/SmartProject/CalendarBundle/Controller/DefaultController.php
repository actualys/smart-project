<?php

namespace SmartProject\CalendarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SmartProjectCalendarBundle:Default:index.html.twig', array('name' => $name));
    }
}
