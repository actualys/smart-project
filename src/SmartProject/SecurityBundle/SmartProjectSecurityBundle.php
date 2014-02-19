<?php

namespace SmartProject\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartProjectSecurityBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
