<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixture extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE_PREFIX = 'tag';
    public const COUNT = 12;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= static::COUNT; ++$i) {
            $name = 'Tag ' . $i;
            $tag = new Tag($name);

            $this->addReference(static::REFERENCE_PREFIX . $i, $tag);

            $manager->persist($tag);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder(): int
    {
        return 98;
    }
}
