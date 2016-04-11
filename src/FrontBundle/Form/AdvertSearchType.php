<?php
namespace FrontBundle\Form;

use Doctrine\ORM\EntityManager;
use MainBundle\Entity\AdvertSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\Validator\Constraints\Regex;

class AdvertSearchType extends AbstractType
{
    protected $keyword;
    protected $category;
    protected $city;
    protected $page;
    protected $em;

    public function __construct(EntityManager $em = null, $keyword = null, $category = null, $city = null, $page = null) {
        $this->keyword = $keyword;
        $this->category = $category;
        $this->city = $city;
        $this->page = $page;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $data = $this->category ? $this->em->getReference("MainBundle:Category", $this->category): null;

        $builder
            ->add('keyword','text',
                array(
                    'required' => false,
                    /*'constraints' => array(
                        new Regex("/^\w+/")
                    )*/
                )
            )
            ->add('category'
                , 'entity',
                array(
                    'class' => 'MainBundle\Entity\Category',
                    'choice_label' => 'title',
                    'property' => 'id',
                    'multiple' => false,
                    'expanded' => false,
                    'read_only' => true,
                    'empty_value' => 'Choisissez une catégorie',
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                        return $er->createQueryBuilder('c');
                    },
                    'data' => $data,
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
            'csrf_protection' => false,
            /*'data_class' => 'MainBundle\Entity\AdvertSearch',
            'empty_data' => function (FormInterface $form) {
                $keyword = $form->get('keyword')->getData() ? $form->get('keyword')->getData() : null;
                $category = $form->get('category')->getData() ? $form->get('category')->getData()->getId() : null;
                $city = $form->get('city')->getData() ? $form->get('city')->getData() : null;
                die('dfgd');
                return new AdvertSearch(
                    $keyword,
                    $category,
                    $city
                );
            }*/
        ));
    }


    public function getName()
    {
        return 'search_form';
    }
}