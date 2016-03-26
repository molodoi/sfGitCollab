<?php
namespace FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword','text',
                array(
                    'required' => false
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
                    'empty_value' => 'Choisissez une catégorie'
                )
            )
            ->add('city','text',
                array(
                    'required' => false
                )
            )
        ;
    }

    public function getName()
    {
        return 'front_advert_search_form';
    }
}