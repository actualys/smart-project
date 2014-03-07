<?php

namespace SmartProject\SecurityBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use SmartProject\TimesheetBundle\Entity\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="security_user")
 */
class User extends BaseUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        // TODO
    }
}
