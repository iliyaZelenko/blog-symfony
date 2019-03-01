<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\App\Controllers;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends Fixture implements OrderedFixtureInterface
{
    // для каждого поста создать столько комментов
    public const COMMENT_REFERENCE_EACH_COUNT = 10;
    // вероятность поставить комменту родителя из текущего поста (но из текущего поста может и не быть коммента)
    public const PROBABILITY_SET_PARENT = 1 / 1.5;

    public function load(ObjectManager $manager): void
    {
        $repo = $manager->getRepository(Comment::class);
        $totalComments = static::COMMENT_REFERENCE_EACH_COUNT * PostFixture::COUNT;

        for ($i = 1; $i <= $totalComments; ++$i) {
            // индекс текущего поста
            $currentPostIndex = ceil($i / static::COMMENT_REFERENCE_EACH_COUNT);

            // автор коммента
            $userRef = UserFixture::getRandomReference();
            $user = $this->getReference($userRef);
            // пост коммента
            $postRef = PostFixture::REFERENCE_PREFIX . $currentPostIndex;
            $post = $this->getReference($postRef);

            $text = 'Comment text ' . $i;

            $comment = new Comment($user, $post, $text);

            // ставит коммент-родителя с долей вероятности
            if (random_int(1, 10) / 10 <= static::PROBABILITY_SET_PARENT) {
                $randomComment = $this->getRandomCommentFromCurrentPost($repo, $currentPostIndex, $i);

                $randomComment && $comment->setParent($randomComment);
            }

            $manager->persist($comment);
            $manager->flush();
        }
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 102;
    }

    /**
     * Возвращает id первого коммента.
     *
     * @param App\Controllers $repo
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @return int|null
     */
    private function getFirstCommentId(App\Controllers $repo): ?int
    {
        $first = $repo->getFirst();

        if ($first) {
            return $first->getId();
        }

        return null;
    }

    /**
     * Возвращает случайный коммент из текущего поста (с индксом $currentPostIndex).
     *
     * @param $repo
     * @param $currentPostIndex
     * @param $currentCommentIndex
     * @throws \Exception
     * @return Comment|null
     */
    private function getRandomCommentFromCurrentPost($repo, $currentPostIndex, $currentCommentIndex): ?Comment
    {
        // начальный индекс коммента для текущего поста
        // если COMMENT_REFERENCE_EACH_COUNT === 10, то для первого поста будет 0, для второго - 10, потом 20, 30...
        $currentPostCommentsStart = ($currentPostIndex - 1) * static::COMMENT_REFERENCE_EACH_COUNT;

        $firstId = $this->getFirstCommentId($repo);

        $parentRangeStart = (int) round($firstId + $currentPostCommentsStart);
        $parentRangeEnd = (int) max($parentRangeStart + $currentCommentIndex - 2, $parentRangeStart);

        // пусть будет для теста
//        dump([
//            'comment' => $i,
//            'post' => $currentPostIndex,
//            'min' => $parentRangeStart,
//            'max' => $parentRangeEnd,
//            'firstId' => $firstId,
//            '$currentPostIndexCommentsStart' => $curruntPostCommentsStart
//        ]);

        if ($firstId) {
            $randomComment = $repo->find(
                random_int(
                    $parentRangeStart,
                    $parentRangeEnd
                )
            );

            return $randomComment;
        }

        return null;
    }
}
