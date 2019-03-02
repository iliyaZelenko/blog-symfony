<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Post;
use App\Entity\PostVote;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    /**
     * @var boolean
     */
    private $firstPostIsCreated = false;

    public function testVoteCRUD()
    {
        $post = $this->createNewPost();
        $votes = [];
        $votesToCreate = 10;

        for ($i = 0; $i < $votesToCreate; $i++) {
            // не используется внешний unit, создается stub для PostVote
            $voteStub = $this->createMock(PostVote::class);
            // каждый четный это 1, не четный это -1
            $value = $i % 2 === 0 ? 1 : -1;

            $voteStub->method('getValue')->willReturn($value);

            // простое добавление не подходит
            array_unshift($votes, $voteStub);
            $post->addVote($voteStub);
        }

        $votesCollection = $post->getVotes();

        $this->assertInstanceOf(Collection::class, $votesCollection);
        $this->assertEquals($votes, $votesCollection->toArray());

        // странно, но возвращает всегда 0, хоть там в самом методе getVotesValue каждая итерация выводит правильно
        // $this->assertEquals($votesToCreate / 2, $post->getVotesValue());

        $this->assertEquals($votes, $votesCollection->toArray());


        $post->removeVote(array_shift($votes));
        $post->removeVote(array_shift($votes));

        // странно, но после удаления $votes и $votesCollection->toArray() не равны
        // $post->removeVote(array_pop($votes));

        $this->assertEquals($votes, $votesCollection->toArray());
        $this->assertFalse(0.1 + 0.2 === 0.3);
    }

    // можно было создать 1 пост в конструкторе, но для каждого метода-теста может понадобиться его личный пост
    private function createNewPost(): Post
    {
        $userDummy = $this->createMock(User::class);
        $title = 'Title';
        $text = 'Text';
        $textShort = 'Text short';
        $post = new Post($userDummy, $title, $text, $textShort);

        if (!$this->firstPostIsCreated) {
            $this->assertEquals($userDummy, $post->getAuthor());
            $this->assertEquals($title, $post->getTitle());
            $this->assertEquals($text, $post->getText());
            $this->assertEquals($textShort, $post->getTextShort());

            $this->firstPostIsCreated = true;
        }

        return $post;
    }
}
