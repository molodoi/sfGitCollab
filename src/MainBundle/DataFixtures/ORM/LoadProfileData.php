<?php
namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MainBundle\Entity\Profile;

class LoadProfileData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $profileAdmin = new Profile();
        $profileAdmin->setFirstname('user');
        $profileAdmin->setLastname('user');
        $profileAdmin->setPhone('0328491555');
        $profileAdmin->setMobile('0633294577');
        $profileAdmin->setAddress('1 rue user');
        $profileAdmin->setZipcode('59270');
        $profileAdmin->setCity('Bailleul');
        $profileAdmin->setCountry('France');
        $profileAdmin->setLatitude(21);
        $profileAdmin->setLongitude(21);
        $profileAdmin->setPhonePublic(1);
        $profileAdmin->setMobilePublic(1);
        $profileAdmin->setAddressPublic(1);

        $manager->persist($profileAdmin);

        $profileAdmin1 = new Profile();
        $profileAdmin1->setFirstname('user1');
        $profileAdmin1->setLastname('user1');
        $profileAdmin1->setPhone('0328491555');
        $profileAdmin1->setMobile('0633294577');
        $profileAdmin1->setAddress('1 rue user1');
        $profileAdmin1->setZipcode('59270');
        $profileAdmin1->setCity('Bailleul');
        $profileAdmin1->setCountry('France');
        $profileAdmin1->setLatitude(21);
        $profileAdmin1->setLongitude(21);
        $profileAdmin1->setPhonePublic(1);
        $profileAdmin1->setMobilePublic(1);
        $profileAdmin1->setAddressPublic(1);

        $manager->persist($profileAdmin1);

        $profileAdmin2 = new Profile();
        $profileAdmin2->setFirstname('user2');
        $profileAdmin2->setLastname('user2');
        $profileAdmin2->setPhone('0328491555');
        $profileAdmin2->setMobile('0633294577');
        $profileAdmin2->setAddress('1 rue user2');
        $profileAdmin2->setZipcode('59270');
        $profileAdmin2->setCity('Bailleul');
        $profileAdmin2->setCountry('France');
        $profileAdmin2->setLatitude(21);
        $profileAdmin2->setLongitude(21);
        $profileAdmin2->setPhonePublic(1);
        $profileAdmin2->setMobilePublic(1);
        $profileAdmin2->setAddressPublic(1);

        $manager->persist($profileAdmin2);

        $manager->flush();

        $this->addReference('profile', $profileAdmin);
        $this->addReference('profile1', $profileAdmin1);
        $this->addReference('profile2', $profileAdmin2);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}