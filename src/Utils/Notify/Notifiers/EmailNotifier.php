<?php

namespace App\Utils\Notify\Notifiers;

use App\Entity\Resources\EmailNotifiableInterface;
use App\Utils\Contracts\Mail\MailInterface;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\EmailNotification;

class EmailNotifier implements NotifierInterface
{
    /**
     * @var MailInterface
     */
    private $mail;

    public function __construct(MailInterface $mail)
    {
        $this->mail = $mail;
    }

    public function supports($willBeNotified, $notification): bool
    {
        return $willBeNotified instanceof EmailNotifiableInterface
            && $willBeNotified->getEmail()
            // && $willBeNotified->hasEnabledEmailNotifications()
            && $notification instanceof EmailNotification
        ;
    }

    /**
     * @param EmailNotifiableInterface $willBeNotified
     * @param EmailNotification $notification
     */
    public function notify($willBeNotified, $notification): void
    {
        $email = $willBeNotified->getEmail();
        $subject = $notification->getSubject();
        $message = $notification->getMessage();

//        dump(
//            implode([$email, $subject, $message], ' | ')
//        );

        $this->mail->send($email, $subject, $message);
    }
}
