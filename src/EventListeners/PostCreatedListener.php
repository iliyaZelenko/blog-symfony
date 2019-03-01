<?php

namespace App\EventListeners;

use App\EventListeners\Events\PostCreatedEvent;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notify;

class PostCreatedListener
{
    /**
     * @var Notify
     */
    private $notify;

    public function __construct(
        Notify $notify
    )
    {
        $this->notify = $notify;
    }

    public function onPostCreated(PostCreatedEvent $event): void
    {
//        $post = $event->getPost();
//        $user = $post->getAuthor();
//
//        $title = 'Привет';
//        $text = 'Привет, пользователь!';
//
//        $notificationData = new NotificationData($title, $text);
//        $this->notify->notify($user, $notificationData);
//
//        dump('Post created!');
    }
}
