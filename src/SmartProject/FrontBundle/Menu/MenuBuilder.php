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
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu() // Request $request
    {
        $menu = $this->factory->createItem('root', array(
                'navbar' => true,
            ));

        $users = $menu->addChild('Users', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-user',
                'dropdown' => true,
                'caret' => true,
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

        $projects->addChild('Project listing', array(
                'route' => 'project',
                'icon' => 'glyphicon glyphicon-list-alt',
            ));
        $projects->addChild('Create client', array(
                'route' => 'client_new',
                'icon' => 'glyphicon glyphicon-plus-sign',
            ));
        $projects->addChild('divider_1', array('divider' => true));
        $projects->addChild('Synchronize Redmine', array(
                'route' => 'project_synchronize',
                'icon' => 'glyphicon glyphicon-refresh',
            ));

        $todo = $menu->addChild('Todo List', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-list',
                'dropdown' => true,
                'caret' => true,
            ));
        $todo->addChild('Listing', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-list-alt',
            ));
        $todo->addChild('Add task', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-plus-sign',
            ));
        $todo->addChild('divider_3', array('divider' => true));
        $todo->addChild('Archives', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-folder-close',
            ));

        $timesheet = $menu->addChild('Timesheet', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-dashboard',
                'dropdown' => true,
                'caret' => true,
            ));
        $timesheet->addChild('Timeline', array(
                'route' => 'timeline',
                'icon' => 'glyphicon glyphicon-time',
            ));
        $timesheet->addChild('Matrix', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-th',
            ));
        $timesheet->addChild('divider_2', array('divider' => true));
        $timesheet->addChild('Consolidation', array(
                'uri' => '#',
                'icon' => 'glyphicon glyphicon-edit',
            ));

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createUserMenu() // Request $request
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
