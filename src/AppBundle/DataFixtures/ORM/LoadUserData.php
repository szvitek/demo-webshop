<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 04.
 * Time: 9:56
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UserBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $encoder = $this->container->get('security.password_encoder');

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@webshop.com');
        $password = $encoder->encodePassword($admin, 'adminpass');
        $admin->setPassword($password);
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('user');
        $user->setEmail('user@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('john');
        $user->setEmail('john@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $manager->persist($user);

        $user = new User();
        $user->setUsername('jane');
        $user->setEmail('jane@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }


}