<?php

namespace App\EventListeners\Events;

use App\Entity\Comment;
use Symfony\Component\EventDispatcher\Event;

/**
 * The "comment.created" event is dispatched each time an post is created
 */
class CommentCreatedEvent extends Event
{
    public const NAME = 'comment.created';

    /**
     * @var Comment
     */
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }
}
