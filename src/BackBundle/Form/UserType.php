<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text')
            ->add('email', 'email')
            ->add('roles', 'choice', array(
                'choices' => array(
                    'ROLE_USER'         => 'USER',
                    'ROLE_ADMIN'        => 'ADMIN',
                    'ROLE_SUPER_ADMIN'  => 'SUPER ADMIN',
                ),
                'multiple' => true,
            ))
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'required' => $options['passwordRequired'],
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('firstname')
            ->add('lastname')
            ->add('phone')
            ->add('mobile')
            ->add('address')
            ->add('zipcode')
            ->add('city')
            ->add('country')
            ->add('complement')
            ->add('phonePublic')
            ->add('mobilePublic')
            ->add('addressPublic')
            ->add('longitude')
            ->add('latitude')
            ->add('file')
        ;
        if ($options['lockedRequired']) {
            $builder->add('locked', null, array('required' => false,
                'label' => 'Vérouiller le compte'));
        }
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\User',
            'passwordRequired' => true,
            'lockedRequired' => false,
        ));
    }
}
