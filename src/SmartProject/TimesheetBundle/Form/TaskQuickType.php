<?php

namespace SmartProject\TimesheetBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskQuickType extends AbstractType
{
    /**
     * @var array
     */
    private $tasks = array();

    /**
     * @param array $tasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
    }

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
          ->add('task', 'chosen', array(
                  'label' => false,
                  'choices' => $this->tasks,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append' => array(
                      'icon' => 'th-large',
                  ),
                  'attr' => array(
                      'data-placeholder' => 'Client / Project',
                      'autocomplete' => 'off',
                  ),
                  'required' => false,
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
