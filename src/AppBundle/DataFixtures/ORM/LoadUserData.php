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
        $admin->setRoles(array('ROLE_ADMIN'));
        $admin->setFullName('Tony Stark');
        $admin->setAddress('64 Hampton Court Rd');
        $admin->setCity('SPALEFIELD');
        $admin->setCounty('county1');
        $admin->setZip('KY10 1ZT');
        $admin->setCountry('UK');
        $manager->persist($admin);

        $user = new User();
        $user->setUsername('hulk');
        $user->setEmail('hulk@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $user->setFullName('Bruce Banner');
        $user->setAddress('33 Spilman Street');
        $user->setCity('GOUDHURST');
        $user->setCounty('county2');
        $user->setZip('TN17 4UY');
        $user->setCountry('UK');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('deadpool');
        $user->setEmail('deadpool@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $user->setFullName('Wade Wilson');
        $user->setAddress('91 Hart Road');
        $user->setCity('NORTHBOURNE');
        $user->setCounty('county3');
        $user->setZip('CT14 6GR');
        $user->setCountry('UK');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('blackwidow');
        $user->setEmail('blackwidow@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $user->setFullName('Natasha Romanoff');
        $user->setAddress('3 Davids Lane');
        $user->setCity('SULLINGTON');
        $user->setCounty('county4');
        $user->setZip('RH20 3XD');
        $user->setCountry('UK');
        $manager->persist($user);

        $user = new User();
        $user->setUsername('captain');
        $user->setEmail('captain@webshop.com');
        $userPass = $encoder->encodePassword($user, 'userpass');
        $user->setPassword($userPass);
        $user->setFullName('Steve Rogers');
        $user->setAddress('36 Walden Road');
        $user->setCity('GRENOSIDE');
        $user->setCounty('county5');
        $user->setZip('S30 2RZ');
        $user->setCountry('UK');
        $manager->persist($user);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }


}