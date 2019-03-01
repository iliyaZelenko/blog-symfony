<?php

namespace App\Form\DataObjects\PostVote;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

final class PostVoteCreationData
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Post
     */
    private $post;

    /**
     * @var string
     * @Assert\Type(type="int")
     */
    private $voteValue;

    public function __construct(User $user, Post $post, int $voteValue)
    {
        $this->user = $user;
        $this->post = $post;
        $this->voteValue = $voteValue;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param Post $post
     * @return self
     */
    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    /**
     * @return string
     */
    public function getVoteValue(): string
    {
        return $this->voteValue;
    }

    /**
     * @param string $voteValue
     */
    public function setVoteValue(string $voteValue): void
    {
        $this->voteValue = $voteValue;
    }
}
