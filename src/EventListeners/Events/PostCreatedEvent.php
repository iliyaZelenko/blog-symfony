<?php

namespace App\EventListeners\Events;

use App\Entity\Post;
use Symfony\Component\EventDispatcher\Event;

/**
 * The "post.created" event is dispatched each time an post is created
 */
class PostCreatedEvent extends Event
{
    public const NAME = 'post.created';

    /**
     * @var Post
     */
    private $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }
}
