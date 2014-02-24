<?php

namespace SmartProject\FrontBundle\Twig;

/**
 * Class ArrayExtension
 *
 * @package SmartProject\FrontBundle\Twig
 */
class ArrayExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'implode' => new \Twig_Filter_Method($this, 'implodeFilter'),
        );
    }

    public function implodeFilter($array, $separator = ',')
    {
        return implode($array, $separator);
    }

    public function getName()
    {
        return 'smartproject_array';
    }
} 