<?php

namespace App\Entity;

use App\Entity\Resources\CreatedUpdatedInterface;
use App\Entity\Resources\SluggableInterface;
use App\Entity\Resources\CreatedUpdatedTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// use Symfony\Component\Validator\Constraints\Collection;
// use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// @UniqueEntity("slug")
/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post implements SluggableInterface, CreatedUpdatedInterface
{
    use CreatedUpdatedTrait;

    /* Columns */

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $textShort;

    // было unique=true, но пост ищется по id, поэтому конфликта не будет, ничего страшного думаю не будет
    // если одинаковый слуг, он же относится к контенту, не нужно будет делать проверку уникальнсоти слугов и для
    // сохранения уникальности добавлять число на конец слуга, которое только будет мешать seo(не относится к заголовку)
    // Например, допустим посты начали писать разные пользователи, один сделал пост со слугом "kak-rabotat-s-symfony-4"
    // (Как работать с Symfony 4), потом второй сделал такой же заголовок, но слуг уже будет "kak-rabotat-s-symfony-42"
    // (добавилось 2 для уникальности слуга), получилось что версия не 4, а 42, как по мне, это меняет смысл поста,
    // из-за чего плохо для сео.
    /**
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /* Relations */

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post", orphanRemoval=true)
//     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
//     */
//    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="Tag")
     * @ORM\JoinTable(
     *   name="post_tag",
     *   joinColumns={
     *     @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tags;

    //, inversedBy="posts"
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostVote", mappedBy="post", orphanRemoval=true)
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @ORM\OrderBy(value={"createdAt" = "DESC"})
     */
    private $votes;

    /**
     * Post constructor.
     * @param User $author
     * @param string $title
     * @param string $text
     * @param string $textShort
     * @param Tag[] $tags
     */
    public function __construct(User $author, string $title, string $text, string $textShort, $tags = [])
    {
//        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->tags = new ArrayCollection();

        foreach ($tags as $tag) {
            $this->addTag($tag);
        }

//        $author->addPost($this);
        $this
            ->setAuthor($author)
            ->setTitle($title)
            ->setText($text)
            ->setTextShort($textShort);
    }

    public function getSlugAttributes(): array
    {
        return [
            // TODO если указывать метод setSlug => setTitle то их использовать
            'slug' => 'title'
        ];
    }

    /* Getters / Setters */

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getTextShort(): string
    {
        return $this->textShort;
    }

    public function setTextShort(string $textShort): self
    {
        $this->textShort = $textShort;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    /* Relations */

    public function getVotesValue(): int
    {
//        $sum = array_reduce(
//            $this->votes->toArray(),
//            function ($perv, PostVote $curr) {
//                return $perv + $curr->getValue();
//            }
//        );

        $sum = array_sum(
            array_map(function (PostVote $vote) {
                return $vote->getValue();
            }, $this->votes->toArray())
        );

        return $sum;
    }

    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(PostVote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes[] = $vote;
        }

        return $this;
    }

    public function removeVote(PostVote $vote): self
    {
        if ($this->votes->contains($vote)) {
            $this->votes->removeElement($vote);
            // set the owning side to null (unless already changed)
            if ($vote->getPost() === $this) {
                $vote->setPost(null);
            }
        }

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /* Other */

    public function getUserVoteValue(User $user): ?Int
    {
        if (!$vote = $this->getUserVote($user)) {
            return null;
        }

        return $vote->getValue();
        // Если бы PHP возвращал не bool для логических: return ($vote = $this->getUserVote($user)) && $vote->getValue()
    }

    /**
     * @param User $user
     * @return PostVote | null
     */
    public function getUserVote(User $user): ?PostVote
    {
        $foundVote = null;

        // не нашел более удобную функцию :( Нужно как find в JS.
        $this->votes->forAll(function ($key, PostVote $vote) use ($user, &$foundVote) {
            if ($vote->getUser()->getId() === $user->getId()) {
                $foundVote = $vote;

                return false;
            }

            return true;
        });

        return $foundVote;
    }
}
