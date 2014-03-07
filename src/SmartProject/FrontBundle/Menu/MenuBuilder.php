<?php

namespace SmartProject\FrontBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MenuBuilder
 *
 *  navbar
 *  pills
 *  stacked
 *  dropdown-header
 *  dropdown
 *  list-group
 *  list-group-item
 *  caret
 *  pull-right
 *  icon
 *  divider
 *
 * @package SmartProject\FrontBundle\Menu
 */
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

    /**
     * @param Request $request
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root', array(
                'navbar' => true,
            ));

        $users = $menu->addChild('Users', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-user',
            ));
        $users->addChild('Users', array(
                'uri' => '#',
            ));
        $users->addChild('Groups', array(
                'uri' => '#',
            ));

        $projects = $menu->addChild('Projects', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-th-large',
                'dropdown' => true,
                'caret' => true,
            ));

        $projects->addChild('Clients list', array(
                'route' => 'client',
            ));
        $projects->addChild('Projects list', array(
                'route' => 'project',
            ));
        $projects->addChild('divider_1', array('divider' => true));
        $projects->addChild('Synchronize Redmine', array(
                'route' => 'project_synchronize',
            ));

        $todo = $menu->addChild('Todo List', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-list',
            ));
        $todo->addChild('Synchronize Redmine', array(
                'uri' => '#',
            ));

        $timesheet = $menu->addChild('Timesheet', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-dashboard',
                'dropdown' => true,
                'caret' => true,
            ));
        $timesheet->addChild('Timeline', array(
                'route' => 'timeline',
            ));
        $timesheet->addChild('Matrix', array(
                'uri' => '#',
            ));
        $timesheet->addChild('divider_2', array('divider' => true));
        $timesheet->addChild('Consolidation', array(
                'uri' => '#',
            ));

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
