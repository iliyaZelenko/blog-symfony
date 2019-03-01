<?php

namespace App\EventListeners;

use App\EventListeners\Events\CommentCreatedEvent;
use App\Utils\Notify\EventsNotificators\CommentCreatedNotificator;

class CommentCreatedListener
{
    /**
     * @var CommentCreatedNotificator
     */
    private $commentCreatedNotificator;

    public function __construct(
        CommentCreatedNotificator $commentCreatedNotificator
    )
    {
        $this->commentCreatedNotificator = $commentCreatedNotificator;
    }

    public function onCommentCreated(CommentCreatedEvent $event): void
    {
        // по принципу единой ответственности отделил в отдельный класс
        $this->commentCreatedNotificator->notify($event);
    }
}
