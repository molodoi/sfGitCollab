<?php
namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use MainBundle\Entity\Advert;

class LoadAdvertData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $datetime = new \DateTime();
        $datetime->modify('+30 day');

        for ($i = 1; $i < 30; $i++) {
            $ad = new Advert();
            $ad->setSlug('annonce-'.$i);
            $ad->setTitle('annonce '.$i);
            $ad->setContent('Content annonce '.$i.' lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. ');

            $ad->setPrice(2*$i);
            if($i % 2 != 0){
                $ad->setIsActivated(1);
                $ad->setIsPublic(0);
                $ad->setType('offer');
                $ad->setLocation('Lille');
                $ad->setCategory($manager->merge($this->getReference('legumes_cat')));
                $ad->setUser($manager->merge($this->getReference('user3')));
                $ad->setCreatedAt(new \DateTime());
                $ad->setExpiredAt($datetime);
            }else{
                $ad->setIsActivated(1);
                $ad->setIsPublic(1);
                $ad->setType('offer');

                if($i <= 10) {
                    $ad->setCategory($manager->merge($this->getReference('fruits_cat')));
                }else{
                    $ad->setCategory($manager->merge($this->getReference('legumes_cat')));
                }
                if($i <= 10){
                    $ad->setUser($manager->merge($this->getReference('user2')));
                    $ad->setCreatedAt(new \DateTime());
                    $ad->setExpiredAt($datetime);
                    $ad->setLocation('Lille');
                }else if($i > 10 && $i <= 20){
                    $ad->setUser($manager->merge($this->getReference('user3')));
                    $ad->setCreatedAt(new \DateTime());
                    $ad->setExpiredAt($datetime);
                    $ad->setLocation('Paris');
                }else{
                    $ad->setUser($manager->merge($this->getReference('user4')));
                    $ad->setCreatedAt(new \DateTime());
                    $ad->setExpiredAt($datetime);
                    $ad->setLocation('Lyon');
                }
            }

            $ad->setToken(uniqid());


            $manager->persist($ad);
        }

        for ($i = 30; $i <= 60; $i++) {
            $ad2 = new Advert();
            $ad2->setSlug('annonce-'.$i);
            $ad2->setTitle('annonce '.$i);
            $ad2->setContent('Content annonce '.$i.' lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. ');

            $ad2->setPrice(2*$i);
            if($i % 2 != 0){
                $ad2->setIsActivated(0);
                $ad2->setIsPublic(0);
                $ad2->setType('offer');
                $ad2->setLocation('Lyon');
                $ad2->setCategory($manager->merge($this->getReference('fruits_cat')));
                $ad2->setUser($manager->merge($this->getReference('user2')));
            }else{
                $ad2->setIsActivated(1);
                $ad2->setIsPublic(0);
                $ad2->setType('request');
                $ad2->setLocation('Paris');
                $ad2->setCategory($manager->merge($this->getReference('legumes_cat')));
                $ad2->setUser($manager->merge($this->getReference('user4')));
            }
            $ad2->setToken(uniqid());
            $ad2->setCreatedAt(new \DateTime());
            $ad2->setExpiredAt($datetime);
            $manager->persist($ad2);
        }


        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}