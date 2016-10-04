<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 04.
 * Time: 10:12
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categories = $manager->getRepository('AppBundle:Category')->findAll();
        $max = count($categories);

        for($i=1;$i<=120;$i++){
            $product = new Product();
            $product->setName('Test Product '. $i);
            $category = $categories[rand(0,$max-1)];
            $product->setCategory($category);
            $product->setPrice(rand(10,1000));
            $product->setQuantity(rand(0,30));
            $product->setIsFeatured((bool)rand(0,1));
            $product->setDescription(
                $category->getName() . ' -> ' . $product->getName() . ' -> $' . $product->getPrice() .
                ' -> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'
            );
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }


}