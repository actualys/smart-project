<?php

namespace SmartProject\SyncBundle\Form\Provider;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProviderRedmineType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('enabled')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('deletedAt')
            ->add('apiUser')
            ->add('apiKey')
            ->add('url')
            ->add('login')
            ->add('password')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SmartProject\SyncBundle\Entity\Provider\ProviderRedmine'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartproject_syncbundle_provider_providerredmine';
    }
}
