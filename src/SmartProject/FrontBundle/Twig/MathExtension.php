<?php

namespace SmartProject\FrontBundle\Twig;

/**
 * Class MathExtension
 *
 * @package SmartProject\FrontBundle\Twig
 */
class MathExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
        );
    }

    public function getName()
    {
        return 'smartproject_math';
    }
} 
