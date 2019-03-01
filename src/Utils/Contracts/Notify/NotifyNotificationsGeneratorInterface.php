<?php

namespace App\Utils\Contracts\Notify;

use App\Exceptions\AppException;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\NotificationData;

interface NotifyNotificationsGeneratorInterface
{
    /**
     * Каждый нотификатор поддерживает свой класс уведомления. Преобразовывает $notificationData в уведомление.
     *
     * @param NotifierInterface $notifier
     * @param NotificationData $notificationData
     * @throws AppException
     * @throws \ReflectionException
     */
    public function generateNotificationForNotifier(
        NotifierInterface $notifier,
        NotificationData $notificationData
    );
}
