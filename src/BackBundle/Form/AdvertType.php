<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('price')
            ->add('type', 'choice', array(
                'choices'  => array(
                    'offer' => 'Offre',
                    'request' => 'Demande',
                ),
            ))
            ->add('location')
            ->add('longitude')
            ->add('latitude')
            ->add('isPublic')
            ->add('isActivated')
            ->add('user', 'entity',
                array(
                    'class' => 'MainBundle\Entity\User',
                    'choice_label' => 'username',
                    'multiple' => false,
                    'expanded' => false,
                    'empty_value' => 'Choix du user'
                )
            )
            ->add('category'
                , 'entity',
                array(
                    'class' => 'MainBundle\Entity\Category',
                    'choice_label' => 'title',
                    'multiple' => false,
                    'expanded' => false,
                    'read_only' => true,
                    'empty_value' => 'Catégorie de votre annonce'
                )
            )
            ->add('files',  'collection', array(
                'type'         => new AdvertFileType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'options'  => array(
                    'required'  => false,
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\Advert'
        ));
    }
}
