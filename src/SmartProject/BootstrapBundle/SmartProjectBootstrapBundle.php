<?php

namespace SmartProject\BootstrapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class SmartProjectBootstrapBundle
 *
 * @package SmartProject\BootstrapBundle
 */
class SmartProjectBootstrapBundle extends Bundle
{
    public function getParent()
    {
        return 'MopaBootstrapBundle';
    }
}
