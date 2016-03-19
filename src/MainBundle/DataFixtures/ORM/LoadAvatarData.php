<?php
namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MainBundle\Entity\Avatar;

class LoadAvatarData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $avatarAdmin = new Avatar();
        $avatarAdmin->setName('avatar1');
        $avatarAdmin->setPath('ac0f1a8a022e8442a5260d0cdc9eeedc327e4db1.jpeg');
        $manager->persist($avatarAdmin);

        $avatarAdmin2 = new Avatar();
        $avatarAdmin2->setName('avatar');
        $avatarAdmin2->setPath('d25ebe17f40e8e1c20e11296b51d1727fe6f7bb0.jpeg');
        $manager->persist($avatarAdmin2);

        $manager->flush();

        $this->addReference('avatar1', $avatarAdmin);
        $this->addReference('avatar2', $avatarAdmin2);

    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}