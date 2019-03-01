<?php

namespace App\Utils\Notify\Notifiers\NotificationsGenerators;

use App\Utils\Contracts\Notify\Notifiers\NotificationsGenerators\NotificationGeneratorInterface;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notifications\SMSNotification;
use App\Utils\Notify\Notifiers\SMSNotifier;

class SMSNotifierNotificationGenerator implements NotificationGeneratorInterface
{
    /**
     * @inheritdoc
     * @param SMSNotifier $notificationData
     */
    public function generateNotificationForNotifier(
        NotificationData $notificationData,
        NotifierInterface $notifier
    ): SMSNotification
    {
        $message = $notificationData->getText();

        return new SMSNotification($message);
    }
}
