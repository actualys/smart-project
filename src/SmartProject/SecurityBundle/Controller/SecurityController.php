<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SmartProject\SecurityBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;

/**
 * Class SecurityController
 *
 * @package SmartProject\SecurityBundle\Controller
 */
class SecurityController extends BaseSecurityController
{
    /**
     * @param Request $request
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $token = $this->container->get('security.context')->getToken();

        if (!$token instanceof AnonymousToken) {
            $path = $this->container->getParameter('smart_project_security.login.path');
            $url  = $this->container->get('router')->generate($path);

            return new RedirectResponse($url, 302);
        }

        return parent::loginAction($request);
    }
}
