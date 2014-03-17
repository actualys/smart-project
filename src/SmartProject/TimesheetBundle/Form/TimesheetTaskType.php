<?php

namespace SmartProject\TimesheetBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TimesheetTaskType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
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
                'class'                          => 'SmartProject\ProjectBundle\Entity\BaseProject',
                'property'                       => 'parentedName',
                'query_builder'                  => function (EntityRepository $er) {
                      return $er->createQueryBuilder('c')
                        ->orderBy('c.root', 'ASC')
                      ->addOrderBy('c.lft', 'ASC');
                  },
            )
        );
//        $builder->add(
//            'project',
//            'entity',
//            array(
//                'label'                          => false,
//                'horizontal_input_wrapper_class' => 'col-sm-1',
//                'widget_form_group_attr'         => array(
//                    'class' => 'form-group',
//                ),
//                'widget_addon_append'            => array(
//                    'icon' => 'th-large',
//                ),
//                'attr'                           => array(
//                    'placeholder'  => 'Project',
//                    'autocomplete' => 'off',
//                    'class'        => 'form-field-select form-field-project',
//                ),
//                'required'                       => false,
//                'class'                          => 'SmartProject\ProjectBundle\Entity\Project',
//                'property'                       => 'clientIdName',
//                'query_builder'                  => function (EntityRepository $er) {
//                      return $er->createQueryBuilder('p')
//                        ->orderBy('p.root', 'ASC')
//                        ->addOrderBy('p.lft', 'ASC');
//                  },
//            )
//        );
//        $builder->add(
//            'contract',
//            'entity',
//            array(
//                'label'                          => false,
//                'horizontal_input_wrapper_class' => 'col-sm-1',
//                'widget_form_group_attr'         => array(
//                    'class' => 'form-group',
//                ),
//                'widget_addon_append'            => array(
//                    'icon' => 'th-large',
//                ),
//                'attr'                           => array(
//                    'placeholder'  => 'Contract',
//                    'autocomplete' => 'off',
//                    'class'        => 'form-field-select form-field-contract',
//                ),
//                'required'                       => false,
//                'class'                          => 'SmartProject\ProjectBundle\Entity\Contract',
//                'property'                       => 'projectIdName',
//                'query_builder'                  => function (EntityRepository $er) {
//                      return $er->createQueryBuilder('c')
//                        ->orderBy('c.name', 'ASC');
//                  },
//            )
//        );
        $builder->add(
            'description',
            null,
            array(
                'label'               => false,
                'horizontal_input_wrapper_class' => 'col-sm-12',
                'widget_addon_append' => array(
                    'icon' => 'pencil',
                ),
                'attr'                => array(
                    'placeholder' => 'Description',
                    'class'       => 'field-description',
                ),
            )
        );

        for ($i = 1; $i <= 7; $i++) {
            $builder->add(
                'duration_day' . $i,
                null,
                array(
                    'label'                          => false,
                    'horizontal_input_wrapper_class' => '',
                    'attr'                           => array(
                        'placeholder' => '',
                        'class'       => 'field-duration field-duration-day' . $i,
                        'style' => 'padding-left: 3px; padding-right: 3px',
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
                'data_class'      => 'SmartProject\TimesheetBundle\Form\TimesheetTaskModel',
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
        return 'smartproject_collection_task';
    }
}
