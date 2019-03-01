<?php

namespace App\Utils\Notify\EventsNotificators;

use App\EventListeners\Events\CommentCreatedEvent;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notify;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class CommentCreatedNotificator
{
    /**
     * @var Notify
     */
    private $notify;

    /**
     * @var Environment
     */
    private $templating;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        Notify $notify,
        Environment $templating,
        RouterInterface $router
    )
    {
        $this->notify = $notify;
        $this->templating = $templating;
        $this->router = $router;
    }

    /**
     * @param CommentCreatedEvent $event
     * @throws \App\Exceptions\AppException
     * @throws \ReflectionException
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function notify(CommentCreatedEvent $event): void
    {
        $comment = $event->getComment();
        $commentAuthor = $comment->getAuthor();
        $commentPost = $comment->getPost();
        $postAuthor = $commentPost->getAuthor();

        $title = 'Hello, ' . $postAuthor->getUsername();
        $text = 'A user "' . $commentAuthor->getUsername() . '" left a comment under your post "' .
            $commentPost->getTitle(). '". Its text: "' . $comment->getText() . '".'
        ;
        $titleHTML = 'Hello, <b>' . $postAuthor->getUsername() . '</b>';
        $textHTML = 'A user <b>' . $commentAuthor->getUsername() . '</b> left a comment under your post <i>' .
            $commentPost->getTitle(). '</i>. <br> Its text: "' . $comment->getText() . '".'
        ;
        $HTMLBody = $this->templating->render('emails/blog/comment/comment_notification.html.twig', [
            'title' => $titleHTML,
            'text' => $textHTML,
            'postLink' => $this->router->generate('post', [
                'id' => $commentPost->getId(),
                'slug' => $commentPost->getSlug()
            ], UrlGeneratorInterface::ABSOLUTE_URL)
        ]);
        $notificationData = new NotificationData($title, $text);
        $notificationData->setHTMLBody($HTMLBody);


        $this->notify->notify($postAuthor, $notificationData);
    }
}
