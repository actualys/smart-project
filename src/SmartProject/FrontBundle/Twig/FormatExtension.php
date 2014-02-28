<?php

namespace SmartProject\FrontBundle\Twig;

/**
 * Class FormatExtension
 *
 * @package SmartProject\FrontBundle\Twig
 */
class FormatExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'format_duration' => new \Twig_Filter_Method($this, 'durationFilter'),
        );
    }

    public function durationFilter($duration, $separator = ':', $trim = false)
    {
        if (!is_numeric($duration)) {
            return $duration;
        }

        $int = floor($duration);

        if ($trim && $int == $duration) {
            return $int . $separator;
        } else {
            return $int . $separator . str_pad(floor(($duration - $int) * 60), 2, '0', STR_PAD_LEFT);
        }
    }

    public function getName()
    {
        return 'smartproject_duration';
    }
} 