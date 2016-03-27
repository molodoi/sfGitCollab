<?php
namespace FrontBundle\Form;


use MainBundle\Entity\AdvertSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {


        $resolver->setDefaults(array(
            'data_class' => 'MainBundle\Entity\AdvertSearch',
            'empty_data' => function (FormInterface $form) {
                $keyword = $form->get('keyword')->getData() ? $form->get('keyword')->getData() : null;
                $category = $form->get('category')->getData() ? $form->get('category')->getData()->getId() : null;
                $city = $form->get('city')->getData() ? $form->get('city')->getData() : null;
                return new AdvertSearch(
                    $keyword,
                    $category,
                    $city
                );
            }
        ));
    }


    public function getName()
    {
        return 'front_advert_search_form';
    }
}