<?php

namespace SmartProject\TimesheetBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskQuickType extends AbstractType
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
        $builder
          ->add('url', 'hidden')
          ->add(
              'duration',
              'number',
              array(
                  'label'                          => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append'            => array(
                      'icon' => 'time',
                  ),
                  'attr'                           => array(
                      'placeholder'  => 'Duration',
                      'autocomplete' => 'off',
                      'class'        => 'field-control-duration',
                  ),
              )
          )
          ->add(
              'client',
              'entity',
              array(
                  'label'                          => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append'            => array(
                      'icon' => 'th-large',
                  ),
                  'attr'                           => array(
                      'placeholder'  => 'Client',
                      'autocomplete' => 'off',
                      'class'        => 'form-field-select form-field-client',
                  ),
                  'required'                       => false,
                  'class'                          => 'SmartProject\ProjectBundle\Entity\Client',
                  'property'                       => 'name',
                  'query_builder'                  => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                          ->orderBy('c.name', 'ASC');
                    },
              )
          )
          ->add(
              'project',
              'entity',
              array(
                  'label'                          => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_form_group_attr'         => array(
                      'class' => 'form-group hidden',
                  ),
                  'widget_addon_append'            => array(
                      'icon' => 'th-large',
                  ),
                  'attr'                           => array(
                      'placeholder'  => 'Project',
                      'autocomplete' => 'off',
                      'class'        => 'form-field-select form-field-project',
                  ),
                  'required'                       => false,
                  'class'                          => 'SmartProject\ProjectBundle\Entity\Project',
                  'property'                       => 'clientIdName',
                  'query_builder'                  => function (EntityRepository $er) {
                        return $er->createQueryBuilder('p')
                          ->orderBy('p.root', 'ASC')
                          ->addOrderBy('p.lft', 'ASC');
                    },
              )
          )
          ->add(
              'contract',
              'entity',
              array(
                  'label'                          => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_form_group_attr'         => array(
                      'class' => 'form-group hidden',
                  ),
                  'widget_addon_append'            => array(
                      'icon' => 'th-large',
                  ),
                  'attr'                           => array(
                      'placeholder'  => 'Contract',
                      'autocomplete' => 'off',
                      'class'        => 'form-field-select form-field-contract',
                  ),
                  'required'                       => false,
                  'class'                          => 'SmartProject\ProjectBundle\Entity\Contract',
                  'property'                       => 'projectIdName',
                  'query_builder'                  => function (EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                          ->orderBy('c.name', 'ASC');
                    },
              )
          )
          ->add(
              'tags',
              'tag',
              array(
                  'label'                          => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget_addon_append'            => array(
                      'icon' => 'tags',
                  ),
                  'attr'                           => array(
                      'placeholder'  => 'Tags',
                      'autocomplete' => 'off',
                  ),
                  'required'                       => false,
              )
          )
          ->add(
              'date',
              'datepicker',
              array(
                  'label'                          => false,
                  'horizontal_input_wrapper_class' => 'col-sm-12',
                  'widget'                         => 'single_text',
                  'widget_addon_append'            => array(
                      'icon' => 'calendar',
                  ),
                  'attr'                           => array(
                      'autocomplete' => 'off',
                      'placeholder'  => 'Date',
                  ),
              )
          );

        if ($this->viewMode == 'new') {
            $builder
              ->add(
                  'description',
                  null,
                  array(
                      'label'                          => false,
                      'horizontal_input_wrapper_class' => 'col-sm-12',
                      'widget_addon_append'            => array(
                          'icon' => 'pencil',
                      ),
                      'attr'                           => array(
                          'placeholder' => 'Description',
                          'class'       => 'field-description',
                      ),
                  )
              );
        } else {
            $builder
              ->add(
                  'description',
                  'textarea',
                  array(
                      'label'                          => false,
                      'horizontal_input_wrapper_class' => 'col-sm-12',
                      'widget_addon_append'            => array(
                          'icon' => 'pencil',
                      ),
                      'attr'                           => array(
                          'placeholder' => 'Description',
                          'class'       => 'field-description',
                          'style'       => 'height: 130px',
                      ),
                  )
              );
        }
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
