<?php
namespace MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use MainBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $userManager = $this->container->get('fos_user.user_manager');
        // Create our user and set details
        //ADMIN ET SU
        $user = $userManager->createUser();
        $user->setUsername('suadmin');
        $user->setEmail('suadmin@suadmin.fr');
        $user->setPlainPassword('suadmin');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN, SU'));
        $userManager->updateUser($user, true);

        $user1 = $userManager->createUser();
        $user1->setUsername('admin');
        $user1->setEmail('admin@admin.com');
        $user1->setPlainPassword('admin');
        $user1->setEnabled(true);
        $user1->setRoles(array('ROLE_ADMIN'));
        $userManager->updateUser($user1, true);

        //SIMPLE USER CLIENT
        $user2 = $userManager->createUser();
        $user2->setUsername('user');
        $user2->setEmail('user@user.com');
        $user2->setPlainPassword('user');
        $user2->setEnabled(true);
        $user2->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user2, true);

        $user3 = $userManager->createUser();
        $user3->setUsername('user2');
        $user3->setEmail('user2@user2.com');
        $user3->setPlainPassword('user2');
        $user3->setEnabled(true);
        $user3->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user3, true);

        $user4 = $userManager->createUser();
        $user4->setUsername('user3');
        $user4->setEmail('user3@user3.com');
        $user4->setPlainPassword('user3');
        $user4->setEnabled(true);
        $user4->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user4, true);

        //ADMIN ET SU
        $this->addReference('user', $user);
        $this->addReference('user1', $user1);
        //SIMPLE USER CLIENT
        $this->addReference('user2', $user2);
        $this->addReference('user3', $user3);
        $this->addReference('user4', $user4);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 3;
    }
}