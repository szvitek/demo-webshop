<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 10.
 * Time: 14:04
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\OrderSelection;
use AppBundle\Entity\Ordersummary;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadOrderData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $products = $manager->getRepository('AppBundle:Product')->findAll();
        $users = $manager->getRepository('UserBundle:User')->findAll();
        $maxProducts = count($products);
        $maxUsers = count($users);
        
        for ($i=1; $i<=10; $i++){
            $randomUser = $users[rand(0,$maxUsers-1)];
            $summary = new OrderSummary();
            $summary->setUser($randomUser);
            $summary->setFullName($randomUser->getFullName());
            $summary->setAddress($randomUser->getAddress());
            $summary->setCity($randomUser->getCity());
            $summary->setCounty($randomUser->getCounty());
            $summary->setZip($randomUser->getZip());
            $summary->setCountry($randomUser->getCountry());
            $summary->setIsCompleted(rand(0,1));

            $rand = rand(1,5);
            $total = 0;

            for($j=1;$j<=$rand; $j++){
                $randomproduct = $products[rand(0,$maxProducts-1)];
                $selection = new OrderSelection();
                $selection->setSummary($summary);
                $selection->setProduct($randomproduct);
                $selection->setPrice($randomproduct->getPrice());
                $selection->setQuantity(rand(1,10));
                $total += $randomproduct->getPrice() * $selection->getQuantity();
                $manager->persist($selection);
            }
            $summary->setPrice($total);
            $manager->persist($summary);
        }

        $manager->flush();
        
    }

    public function getOrder()
    {
        return 4;
    }


}