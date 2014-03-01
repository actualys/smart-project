<?php

namespace SmartProject\TimesheetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskQuickType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('url', 'hidden')
          ->add('date', 'datepicker', array(
                  'label'  => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget' => 'single_text',
                  'widget_addon_append' => array(
                      'icon' => 'calendar',
                  ),
                  'attr' => array(
                      'daysOfWeekDisabled' => array(0, 6),
                      'autocomplete' => 'off',
                      'placeholder' => 'Date',
                  ),
              ))
          ->add('name', null, array(
                  'label' => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'pencil',
                  ),
                  'attr' => array(
                      'placeholder' => 'Description',
                      'class' => 'field-description',
                  ),
              ))
          ->add('tags', 'tag', array(
                  'label' => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'tags',
                  ),
                  'attr' => array(
                      'placeholder' => 'Tags',
                      'autocomplete' => 'off',
//                      'class' => 'form-field-select',
                  ),
                  'required' => false,
              ))
          ->add('duration', 'number', array(
                  'label' => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'time',
                  ),
                  'attr' => array(
                      'placeholder' => 'Duration',
                      'autocomplete' => 'off',
                  ),
              ))
          ->add('client', 'entity', array(
                  'label' => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'th-large',
                  ),
                  'attr' => array(
                      'placeholder' => 'Client',
                      'autocomplete' => 'off',
                      'class' => 'form-field-select',
                  ),
                  'required' => false,
                  'class' => 'SmartProject\ProjectBundle\Entity\Client',
                  'property' => 'name',
              ))
          ->add('project', 'entity', array(
                  'label' => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'th-large',
                  ),
                  'attr' => array(
                      'placeholder' => 'Project',
                      'autocomplete' => 'off',
                      'class' => 'form-field-select',
                  ),
                  'required' => false,
                  'class' => 'SmartProject\ProjectBundle\Entity\Project',
                  'property' => 'name',
              ))
          ->add('contract', 'entity', array(
                  'label' => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'th-large',
                  ),
                  'attr' => array(
                      'placeholder' => 'Contract',
                      'autocomplete' => 'off',
                      'class' => 'form-field-select',
                  ),
                  'required' => false,
                  'class' => 'SmartProject\ProjectBundle\Entity\Contract',
                  'property' => 'name',
              ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'SmartProject\TimesheetBundle\Form\TaskQuickModel',
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
        return 'smartproject_timesheetbundle_quicktask';
    }
}
