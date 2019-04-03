<?php

namespace App\Entity;

use App\Entity\Resources\CreatedUpdatedInterface;
use App\Entity\Resources\CreatedUpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment implements CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="parent", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $childrenComments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Comment", inversedBy="childrenComments")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    public function __construct(User $author, Post $post, string $text = '')
    {
        $this->childrenComments = new ArrayCollection();

        $this
            ->setAuthor($author)
            ->setPost($post)
            ->setText($text);

//        $author->addComment($this);
//        $post->addComment($this);
    }

    /* Getters / Setters */

    public function getId(): int
    {
        return $this->id;
    }

    // хотел возвращаеть просто ": string" так как поле text не nullable, но если ": ?string", то форма инициализируется с ошибкой
    // update: Поставил в конструкторе $text = '', может уже не актуально
    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    /* Relations */

    public function getPost(): Post
    {
        return $this->post;
    }

    public function setPost(Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(self $comment): self
    {
        $this->parent = $comment;

        return $this;
    }

    public function getChildrenComments(): Collection
    {
        return $this->childrenComments;
    }

    public function addChildrenComment(self $comment): self
    {
        if (!$this->childrenComments->contains($comment)) {
            $this->childrenComments[] = $comment;
            $comment->setParent($this);
        }

        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
