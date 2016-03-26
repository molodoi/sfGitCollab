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

        /*$userManager = $this->container->get('fos_user.user_manager');
        // Create our user and set details
        $user = $userManager->createUser();
        $user->setUsername('test');
        $user->setEmail('test@test.com');
        $user->setPlainPassword('test');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user, true);

        $user1 = $userManager->createUser();
        $user1->setUsername('test1');
        $user1->setEmail('test1@test.com');
        $user1->setPlainPassword('test1');
        $user1->setEnabled(true);
        $user1->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user1, true);

        $user2 = $userManager->createUser();
        $user2->setUsername('test2');
        $user2->setEmail('test2@test.com');
        $user2->setPlainPassword('test2');
        $user2->setEnabled(true);
        $user2->setRoles(array('ROLE_USER'));
        $userManager->updateUser($user2, true);
        $this->addReference('user', $user);
        $this->addReference('user1', $user1);
        $this->addReference('user2', $user2);*/
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}