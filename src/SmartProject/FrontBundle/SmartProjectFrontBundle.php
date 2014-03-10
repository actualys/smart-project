<?php

namespace SmartProject\FrontBundle;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartProjectFrontBundle extends Bundle
{
    const FILTER_SOFTDELETE = 'softdelete';

    /**
     *
     */
    public function boot()
    {
        $doctrine = $this->container->get('doctrine');

        /** @var EntityManager $em */
        $em       = $doctrine->getManager();
        $em->getConfiguration()->addFilter(self::FILTER_SOFTDELETE, 'Gedmo\SoftDeleteable\Filter\SoftDeleteableFilter');
        $em->getFilters()->enable(self::FILTER_SOFTDELETE);
    }
}
