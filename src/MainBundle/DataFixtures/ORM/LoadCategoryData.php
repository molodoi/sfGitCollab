<?php
namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MainBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /*for ($i = 1; $i <= 10; $i++) {
            $category = new Category();
            $category->setTitle('Categorie'.$i);
            $category->setSlug('categorie'.$i);
            $category->setPosition($i);
            $category->setCreatedAt(new \DateTime());
            $manager->persist($category);
            $this->addReference('category'.$i, $category );
        }*/

        $category = new Category();
        $category->setTitle('Legumes');
        $category->setSlug('legumes');
        $category->setPosition(1);
        $category->setCreatedAt(new \DateTime());
        $manager->persist($category);

        $category2 = new Category();
        $category2->setTitle('Fruits');
        $category2->setSlug('fruits');
        $category2->setPosition(2);
        $category2->setCreatedAt(new \DateTime());
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setTitle('Boulangerie');
        $category3->setSlug('boulangerie');
        $category3->setPosition(3);
        $category3->setCreatedAt(new \DateTime());
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setTitle('Patisserie');
        $category4->setSlug('patisserie');
        $category4->setPosition(4);
        $category4->setCreatedAt(new \DateTime());
        $manager->persist($category4);

        $category5 = new Category();
        $category5->setTitle('Cuisine');
        $category5->setSlug('cuisine');
        $category5->setPosition(5);
        $category5->setCreatedAt(new \DateTime());
        $manager->persist($category5);

        $category6 = new Category();
        $category6->setTitle('Alimentation générale');
        $category6->setSlug('alimentation-generale');
        $category6->setPosition(6);
        $category6->setCreatedAt(new \DateTime());
        $manager->persist($category6);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}