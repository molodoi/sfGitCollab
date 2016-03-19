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
            ->add('slug')
            ->add('description')
            ->add('content')
            ->add('price')
            ->add('type')
            ->add('longitude')
            ->add('latitude')
            ->add('token')
            ->add('isAvailable')
            ->add('isPublic')
            ->add('isActivated')
            ->add('deletedAt', 'datetime')
            ->add('expiredAt', 'datetime')
            ->add('createdAt', 'datetime')
            ->add('updatedAt', 'datetime')
            ->add('category')
            ->add('user')
            ->add('tags')
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
