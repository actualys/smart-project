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
        $menu = $this->factory->createItem('root');

        $menu->addChild('Dashboard', array('route' => 'smart_project_front_homepage'));
        $menu->addChild('Projects',  array('route' => 'smart_project_front_homepage'));
        $menu->addChild('Todo list', array('route' => 'smart_project_front_homepage'));
        $sub = $menu->addChild('Timesheet', array('route' => 'smart_project_front_homepage'));
		$sub->addChild('Daily', array('route' => 'smart_project_front_homepage'));
		$sub->addChild('Timeline', array('route' => 'smart_project_front_homepage'));
		$sub->addChild('Weekly', array('route' => 'smart_project_front_homepage'));
        // ... add more children

        return $menu;
    }
}
