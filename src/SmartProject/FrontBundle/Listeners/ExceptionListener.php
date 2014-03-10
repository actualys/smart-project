<?php

namespace SmartProject\FrontBundle\Listeners;

use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Class ExceptionListener
 *
 * @package SmartProject\FrontBundle\Listeners
 */
class ExceptionListener
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var boolean
     */
    protected $debug;

    /**
     * @param EngineInterface $templating
     * @param boolean         $debug
     */
    public function __construct(EngineInterface $templating, $debug)
    {
        $this->templating = $templating;
        $this->debug      = $debug ? true : false;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
     * @throws \Exception
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        static $handling;

        $exception = $event->getException();

        if (true === $handling) {
            return;
        }
        $handling = true;

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            $response = new Response('Internal error: ' . $exception->getMessage(), 500);
            $response->setSharedMaxAge(0);
            $event->setResponse($response);

            return;
        }

        $params = array(
            'title'   => '',
            'message' => ($this->debug ? $exception->getMessage() : ''),
        );

        if ($exception instanceof NotFoundHttpException || $exception instanceof NoResultException) {
            $params['title'] = '404 Not Found';
            $message         = $this->templating->render(
                'SmartProjectFrontBundle:Error:error-404.html.twig',
                $params
            );
            $response        = new Response($message, 404);
            $response->setSharedMaxAge(0);
            $event->setResponse($response);
        } elseif ($exception instanceof AccessDeniedHttpException) {
            $response = new RedirectResponse(
                $this->container->get('router')->generate('fos_user_security_login'),
                302
            );
            $response->setSharedMaxAge(0);
            $event->setResponse($response);
        } else {
            $params['title'] = '500 Internal Error';

            if ($this->debug) {
                echo '<h3>' . get_class($exception) . '</h3>';
                echo '<pre>' . $exception->getMessage() . '</pre>';

                throw $exception;
            }

            $message = $this->templating->render(
                'SmartProjectFrontBundle:Error:error-500.html.twig',
                $params
            );
            $response = new Response($message, 500);
            $response->setSharedMaxAge(0);

            $event->setResponse($response);
        }

        $handling = false;
    }
} 