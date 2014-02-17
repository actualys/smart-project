<?php

namespace SmartProject\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
	 * @Route("/")
	 */
    public function indexAction()
    {
        return $this->render('SmartProjectFrontBundle:Index:index.html.twig');
    }
}
