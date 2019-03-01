<?php

namespace App\Utils\Contracts\Notify\Notifiers\NotificationsGenerators;

use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\NotificationData;

interface NotificationGeneratorInterface
{
    /**
     * Каждый нотификатор поддерживает свой класс уведомления. Преобразовывает $notificationData в уведомление.
     *
     * @param NotificationData $notificationData
     * @param NotifierInterface $notifier
     */
    public function generateNotificationForNotifier(
        NotificationData $notificationData,
        NotifierInterface $notifier
    );
}
