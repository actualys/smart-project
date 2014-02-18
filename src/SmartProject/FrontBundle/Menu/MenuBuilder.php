<?php

namespace SmartProject\FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
                'navbar' => true,
            ));

        $tools = $menu->addChild('Users', array(
                'uri' => '#',
                'icon' => '',
            ));

        $tools = $menu->addChild('Projects', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-th-large',
            ));

        $tools = $menu->addChild('Todo List', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-list',
            ));

        $tools = $menu->addChild('Timesheet', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-dashboard',
            ));


//        $tools->addChild('Symfony', array('uri' => 'http://www.symfony.com'));
//        $tools->addChild('bootstrap', array('uri' => 'https://github.com/twbs/bootstrap'));
//        $tools->addChild('node.js', array('uri' => 'http://nodejs.org/'));
//        $tools->addChild('less', array('uri' => 'http://lesscss.org/'));
//
//        //adding a nice divider
//        $tools->addChild('divider_1', array('divider' => true));
//        $tools->addChild('google', array('uri' => 'http://www.google.com/'));
//        $tools->addChild('node.js', array('uri' => 'http://nodejs.org/'));
//
//        //adding a nice divider
//        $tools->addChild('divider_2', array('divider' => true));
//        $tools->addChild('Mohrenweiser & Partner', array('uri' => 'http://www.mohrenweiserpartner.de'));

        return $menu;
    }

    public function createUserMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
                'navbar' => true,
                'pull-right' => true,
            ));

        $menu->addChild('Settings', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-cog',
            ));

        $menu->addChild('Logout', array(
                'route' => 'fos_user_security_logout',
                'icon' => 'glyphicon glyphicon-log-out',
            ));

        return $menu;
    }
}
