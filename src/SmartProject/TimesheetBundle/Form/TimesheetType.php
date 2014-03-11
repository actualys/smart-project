<?php

namespace SmartProject\TimesheetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimesheetType extends AbstractType
{
    const MODE_NEW = 'new';

    const MODE_EDIT = 'edit';

    /**
     * @var string
     */
    private $viewMode;

    /**
     * @param string $viewMode
     */
    public function __construct($viewMode = self::MODE_NEW)
    {
        $this->viewMode = $viewMode;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date', 'hidden');
        $builder->add(
            'tasks',
            'collection',
            array(
                'label'             => false,
                'type'              => new TimesheetTaskType(),
                'allow_add'         => true,
                'allow_delete'      => true,
                'prototype'         => true,
                'widget_add_btn'    => false,
                'widget_remove_btn' => false,
                'options'           => array(
                    'label_render'      => false,
                    'widget_add_btn'    => false,
                    'widget_remove_btn' => false,
                )
            )
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'SmartProject\TimesheetBundle\Form\TimesheetModel',
                'render_fieldset' => false,
                'show_legend'     => false,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartproject_timesheetbundle_timesheet';
    }
}
