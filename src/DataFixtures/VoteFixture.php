<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\PostVote;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VoteFixture extends Fixture implements OrderedFixtureInterface
{
    public const REFERENCE_PREFIX = 'vote';
    public const COUNT = 35;

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= static::COUNT; ++$i) {
            // TODO пользователь максимум может голосовать 1 раз, но не хотел плодить кучу пользователей.
            /** @var User $user */
            $user = $this->getReference(UserFixture::getRandomReference());
            /** @var Post $post */
            $post = $this->getReference(PostFixture::getRandomReference());
            $value = random_int(0, 1) ? 1 : -1;
            $vote = new PostVote($user, $post, $value);

            $this->addReference(static::REFERENCE_PREFIX . $i, $vote);
            $manager->persist($vote);
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
        return 102;
    }
}
