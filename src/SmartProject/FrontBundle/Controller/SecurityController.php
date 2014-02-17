<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SmartProject\FrontBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\SecurityContext;
use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;

class SecurityController extends BaseSecurityController
{
    public function loginAction(Request $request)
    {
        $token = $this->container->get('security.context')->getToken();

        if (!$token instanceof AnonymousToken) {
            $url = $this->container->get('router')->generate('smart_project_front_homepage');

            return new RedirectResponse($url, 302);
        }

        return parent::loginAction($request);
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data)
    {
        $template = sprintf(
            'SmartProjectFrontBundle:Security:login.html.%s',
            $this->container->getParameter('fos_user.template.engine')
        );

        return $this->container->get('templating')->renderResponse($template, $data);
    }
}
