<?php
/**
 * Created by PhpStorm.
 * User: Szvitek
 * Date: 2016. 10. 04.
 * Time: 3:11
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $names = array("Shirts","T-Shirts","Suits", "Trousers", "Underwear", "Hoodies", "Dresses", "Coats and Jackets", "Tops",
            "Pants and Shorts", "Skirts", "Shoes", "Sandals", "Boots", "Handbags", "Purses and Wallets", "Backpacks", "Kids", "Accessories"
        );

        foreach ($names as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }


}