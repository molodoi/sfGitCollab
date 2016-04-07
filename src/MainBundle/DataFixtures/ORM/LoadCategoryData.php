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
        $category->setTitle('Légumes');
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
        $manager->flush();

        $this->addReference('legumes_cat', $category);
        $this->addReference('fruits_cat', $category2);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}