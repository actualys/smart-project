<?php

namespace SmartProject\ProjectBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $project = $options['project'];

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
          );

        if ($project->getClient()) {
            $builder->add(
                'parent',
                'entity',
                array(
                    'property'                       => 'parentedName',
                    'label'                          => 'Parent project',
                    'horizontal_input_wrapper_class' => 'col-lg-9',
                    'class'                          => 'SmartProject\ProjectBundle\Entity\Project',
                    'required'                       => false,
                    'query_builder'                  => function (EntityRepository $em) use ($project) {
                          $queryBuilder = $em->createQueryBuilder('p')
                            ->where('p.client = :client')
                            ->setParameter(':client', $project->getClient())
                            ->orderBy('p.lft');

                          if ($project->getId()) {
                              $queryBuilder->andWhere('p != :project')
                                ->setParameter(':project', $project);
                          }

                          return $queryBuilder;
                      },
                )
            );
        } else {
            $builder->add(
                'client',
                'entity',
                array(
                    'property'                       => 'name',
                    'label'                          => 'Client',
                    'horizontal_input_wrapper_class' => 'col-lg-9',
                    'class'                          => 'SmartProject\ProjectBundle\Entity\Client',
                    'required'                       => false,
                )
            );
        }

        $builder
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
          ->add(
              'tags',
              'tag',
              array(
                  'label'                          => 'Tags',
                  'horizontal_input_wrapper_class' => 'col-lg-9',
                  'attr'                           => array(
                      'placeholder' => 'Tags',
                  ),
                  'required'                       => false,
              )
          )
          ->add(
              'website',
              'text',
              array(
                  'label'                          => 'Website',
                  'horizontal_input_wrapper_class' => 'col-lg-9',
                  'attr'                           => array(
                      'placeholder' => 'Website',
                  ),
                  'required'                       => false,
              )
          )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class'      => 'SmartProject\ProjectBundle\Entity\Project',
                'render_fieldset' => false,
                'show_legend'     => false,
                'project'         => null,
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smartproject_projectbundle_project';
    }
}
