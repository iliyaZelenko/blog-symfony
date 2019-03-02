<?php

namespace App\Tests\Unit\Entity\Factories;

use App\Entity\Factories\PostVoteFactory;
use App\Entity\Post;
use App\Entity\PostVote;
use App\Entity\User;
use App\Form\DataObjects\PostVote\PostVoteCreationData;
use PHPUnit\Framework\TestCase;

class PostVoteFactoryTest extends TestCase
{
    public function testCreateNew()
    {
        $factory = new PostVoteFactory();
        $userDummy = $this->createMock(User::class);
        $postDummy = $this->createMock(Post::class);

        $creationData = new PostVoteCreationData(
            $userDummy,
            $postDummy,
            -1 // or 1
        );

        $postVote = $factory->createNew($creationData);

        $this->assertInstanceOf(PostVote::class, $postVote);
        $this->assertInstanceOf(User::class, $postVote->getUser());
        $this->assertInstanceOf(Post::class, $postVote->getPost());
    }
}
