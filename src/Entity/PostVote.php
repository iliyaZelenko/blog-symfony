<?php

namespace App\Entity;

use App\Entity\Resources\CreatedUpdatedInterface;
use App\Entity\Resources\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="posts_votes")
 * @ORM\Entity(repositoryClass="App\Repository\PostVoteRepository")
 */
class PostVote implements CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 1 or -1. Можно было сделать через bool, для гибкости решил так.
     *
     * @ORM\Column(type="integer")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="votes")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $post;

    public function __construct(User $user, Post $post, string $value)
    {
        $this
            ->setUser($user)
            ->setPost($post)
            ->setValue($value)
        ;
    }

    /* Getters / Setters */

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return PostVote
     */
    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
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
     * @return PostVote
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Post | null
     */
    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @param Post | null $post
     * @return PostVote
     */
    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
