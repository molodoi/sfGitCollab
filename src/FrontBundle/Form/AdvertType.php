<?php

namespace FrontBundle\Form;

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
            ->add('category')
            ->add('files',  'collection', array(
                'type'         => new AdvertFileType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'options'  => array(
                    'required'  => false,
                ),
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
