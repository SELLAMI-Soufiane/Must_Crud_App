<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

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

        $user = new User();
        $user->setEmail("admin@admin.com");
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            'admin'
        ));
        $user->setRoles(array('ROLE_USER'));
        $manager->persist($user);

        $manager->flush();
    }
}
