<?php

namespace App\Utils\Notify\Notifiers\NotificationsGenerators;

use App\Utils\Contracts\Notify\Notifiers\NotificationsGenerators\NotificationGeneratorInterface;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\EmailNotification;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notifiers\EmailNotifier;

class EmailNotifierNotificationGenerator implements NotificationGeneratorInterface
{
    /**
     * @inheritdoc
     * @param EmailNotifier $notificationData
     */
    public function generateNotificationForNotifier(
        NotificationData $notificationData,
        NotifierInterface $notifier
    ): EmailNotification
    {
        $subject = $notificationData->getTitle();
        $message = $notificationData->getHtmlBody();

        return new EmailNotification($subject, $message);
    }
}
