<?php

namespace App\Form\DataObjects\Comment;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

final class CommentCreationData
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
     * @Assert\NotBlank()
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(
     *     min=5,
     *     max=450
     * )
     */
    private $text;

    /**
     * TODO хотел указать Assert как int или null, похоже нет такой возможности
     *
     * @var int | null
     */
    private $parentCommentId;

    public function __construct(User $user, Post $post)
    {
        $this->user = $user;
        $this->post = $post;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return self
     */
    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return int | null
     */
    public function getParentCommentId(): ?int
    {
        return $this->parentCommentId;
    }

    /**
     * @param int | null $parentCommentId
     * @return self
     */
    public function setParentCommentId(?int $parentCommentId): self
    {
        $this->parentCommentId = $parentCommentId;

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
}
