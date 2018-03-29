<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i = 0; $i < 60; $i++) {

            $product = new Product();
            $product->setTitle('jeu_' . $i);
            $product->SetDescription('Description du jeu ' . $i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
