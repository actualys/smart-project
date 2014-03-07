<?php

namespace SmartProject\SecurityBundle\Listeners;

use FOS\UserBundle\Event\UserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * Class SecurityListener
 *
 * @package SmartProject\FrontBundle\Listeners
 */
class SecurityListener extends ContainerAware implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::SECURITY_IMPLICIT_LOGIN => 'onImplicitLogin',
            'security.interactive_login'           => 'onLoginSuccess',
        );
    }

    /**
     * @param UserEvent $event
     */
    public function onImplicitLogin(UserEvent $event)
    {
        // TODO : log connexion
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onLoginSuccess(InteractiveLoginEvent $event)
    {
        // TODO : log connexion
    }
}
