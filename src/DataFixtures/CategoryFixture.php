<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixture extends Fixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $food = new Category();
        $food->setTitle('Food');
        $manager->persist($food);
        // это вызывает оишбку https://i.imgur.com/rUm139L.png
//         $manager->flush();


        $fruits = new Category();
        $fruits->setTitle('Fruits');
        $fruits->setParent($food);

        $vegetables = new Category();
        $vegetables->setTitle('Vegetables');
        $vegetables->setParent($food);

        $carrots = new Category();
        $carrots->setTitle('Carrots');
        $carrots->setParent($vegetables);

        $manager->persist($food);
        $manager->persist($fruits);
        $manager->persist($vegetables);
        $manager->persist($carrots);
        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 100;
    }
}
