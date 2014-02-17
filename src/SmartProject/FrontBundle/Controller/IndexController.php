<?php

namespace SmartProject\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartProjectFrontBundle:Index:index.html.twig');
    }
}
