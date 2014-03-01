<?php

namespace SmartProject\FrontBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartProjectFrontBundle extends Bundle
{
    /**
     *
     */
    public function boot()
    {
        $doctrine = $this->container->get('doctrine');

        /** @var EntityManager $em */
        $em       = $doctrine->getManager();
        $em->getConfiguration()->addFilter('softdelete', 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter');
        $em->getFilters()->enable('softdelete');
    }
}
