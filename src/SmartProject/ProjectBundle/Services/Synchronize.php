<?php

namespace SmartProject\ProjectBundle\Services;

use Doctrine\ORM\EntityManager;

/**
 * Class Synchronize
 *
 * @package SmartProject\ProjectBundle\Services
 */
class Synchronize
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }


} 