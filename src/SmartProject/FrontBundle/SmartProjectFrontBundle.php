<?php

namespace SmartProject\FrontBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartProjectFrontBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}