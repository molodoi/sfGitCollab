<?php
namespace FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->remove('current_password')
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
            ->add('file','file', array(
                'required' => false
            ));
    }

    public function getParent()
    {
        return 'fos_user_profile';

    }


    // For Symfony 2.x
    public function getName()
    {
        return 'front_user_profile';
    }

}