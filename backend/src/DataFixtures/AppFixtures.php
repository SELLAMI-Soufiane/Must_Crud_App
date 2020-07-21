<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $brand = new Brand();
            $brand->setName('brand '.$i);
            $cat = new Category();
            $cat->setName('cat '.$i);
            for ($j = 0; $j < 5; $j++){
                $product = new Product();
                $product->setBrand($brand);
                $product->setName('product '.$i);
                $product->setDescription('Description'.$i);
                $product->addCategory($cat);
                $manager->persist($product);
            }
            $manager->persist($cat);
            $manager->persist($brand);
        }
        $manager->flush();
    }
}
