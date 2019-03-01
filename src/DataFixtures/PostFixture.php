<?php

namespace App\DataFixtures;

use App\DataFixtures\Traits\RandomReference;
use App\Entity\Post;
use App\Entity\Tag;
use App\Entity\User;
use App\Utils\Contracts\ContentGenerator\ContentGeneratorInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PostFixture extends Fixture implements OrderedFixtureInterface
{
    use RandomReference;

    public const REFERENCE_PREFIX = 'post';
    public const COUNT = 5;

    /**
     * @var ContentGeneratorInterface
     */
    private $contentGenerator;

    public function __construct(ContentGeneratorInterface $contentGenerator)
    {
        $this->contentGenerator = $contentGenerator;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= static::getCount(); ++$i) {
            // случайный автор
            $userRef = UserFixture::getRandomReference();

            /** @var User $user */
            $user = $this->getReference($userRef);

            // для последних двух
            if ($i > static::getCount() - 2) {
                $title = $this->contentGenerator->getRealContent('title');
                $text = $this->contentGenerator->getRealContent('text');
                $textShort = $this->contentGenerator->getRealContent('textShort');
                $tags = $this->getRandomTags();
            } else {
                $title = 'Post title ' . $i;
                $text = 'Post text ' . $i;
                $textShort = 'Post text short ' . $i;
                $tags = $this->getRandomTags();
            }

            $post = new Post($user, $title, $text, $textShort, $tags);

            $this->addReference(static::REFERENCE_PREFIX . $i, $post);

            $manager->persist($post);
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
        return 101;
    }

    /**
     * @throws \Exception
     * @return Tag[]
     */
    private function getRandomTags(): array
    {
        for ($i = 1, $tags = []; $i < TagFixture::COUNT; $i++) {
            // пропускает рандомные теги
            if (random_int(0, 2)) {
                continue;
            }

            $ref = TagFixture::REFERENCE_PREFIX . $i;
            $tags[] = $this->getReference($ref);
        }

        return $tags;
    }
}
