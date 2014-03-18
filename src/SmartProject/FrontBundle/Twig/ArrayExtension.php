<?php

namespace SmartProject\FrontBundle\Twig;

/**
 * Class ArrayExtension
 *
 * @package SmartProject\FrontBundle\Twig
 */
class ArrayExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('implode', array($this, 'implodeFilter')),
            new \Twig_SimpleFilter('key', array($this, 'keyFilter')),
        );
    }

    /**
     * @param array  $array
     * @param string $separator
     *
     * @return string
     */
    public function implodeFilter($array, $separator = ',')
    {
        return implode($array, $separator);
    }

    /**
     * @param array $array
     * @param string $key
     *
     * @return mixed
     */
    public function keyFilter($array, $key)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartproject_array';
    }
} 
