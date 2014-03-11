<?php

namespace SmartProject\ProjectBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContractType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add(
              'name',
              'text',
              array(
                  'label'                          => 'Name',
                  'horizontal_input_wrapper_class' => 'col-lg-9',
                  'attr'                           => array(
                      'placeholder' => 'Name',
                  ),
                  'constraints'                    => new NotBlank(),
              )
          )
          ->add(
              'slug',
              'text',
              array(
                  'label'                          => 'Slug',
                  'horizontal_input_wrapper_class' => 'col-lg-9',
                  'attr'                           => array(
                      'placeholder' => 'Slug',
                  ),
                  'required'                       => false,
              )
          )
//          ->add(
//              'project',
//              'entity',
//              array(
//                  'property'                       => 'name',
//                  'label'                          => 'Project',
//                  'horizontal_input_wrapper_class' => 'col-lg-9',
//                  'class'                          => 'SmartProject\ProjectBundle\Entity\Project',
////                  'required'                       => false,
//              )
//          )
          ->add(
              'description',
              null,
              array(
                  'label'                          => 'Description',
                  'horizontal_input_wrapper_class' => 'col-lg-9',
                  'attr'                           => array(
                      'placeholder' => 'Description',
                  ),
                  'required'                       => false,
              )
          )
//          ->add('status', null, array(
//                  'label'                          => 'Status',
//                  'horizontal_input_wrapper_class' => 'col-lg-9',
//                  'required'                       => false,
//              )
//          )
//          ->add('default', null, array(
//                  'horizontal_input_wrapper_class' => 'col-lg-9',
//                  'required'                       => false,
//              )
//          )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'SmartProject\ProjectBundle\Entity\Contract',
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
        return 'smartproject_projectbundle_contract';
    }
}
