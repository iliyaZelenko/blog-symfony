<?php

namespace App\Utils\Notify;

use App\Exceptions\AppException;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Contracts\Notify\NotifyInterface;
use App\Utils\Contracts\Notify\NotifyNotificationsGeneratorInterface;
use App\Utils\Notify\Notifications\NotificationData;

class Notify implements NotifyInterface
{
    /**
     * Хранилище доступных нотификаторов
     *
     * @var NotifyChain
     */
    private $notifyChain;

    /**
     * @var NotifyNotificationsGeneratorInterface
     */
    private $notificationsGenerator;

    public function __construct(
        NotifyChain $notifyChain,
        NotifyNotificationsGeneratorInterface $notificationsGenerator
    )
    {
        $this->notifyChain = $notifyChain;
        $this->notificationsGenerator = $notificationsGenerator;
    }

    /**
     * Уведомляет пользователя $willBeNotified через все доступные нотификаторы которые поддерживаются для пользователя.
     * Уведомления генерируются из $notificationData
     *
     * @param $willBeNotified - who will be notified (e.g. User)
     * @param $notificationData
     * @throws AppException
     * @throws \ReflectionException
     */
    public function notify($willBeNotified, NotificationData $notificationData): void
    {
        $notifiers = $this->notifyChain->getNotifiers();

        foreach ($notifiers as $notifier) {
            $this->notifyThroughProcess($willBeNotified, $notificationData, $notifier);
        }
    }

    /**
     * Уведомляет через конкретный нотификатор
     *
     * @param $willBeNotified
     * @param NotificationData $notificationData
     * @param NotifierInterface $notifier
     * @throws AppException
     * @throws \ReflectionException
     */
    public function notifyThrough($willBeNotified, NotificationData $notificationData, NotifierInterface $notifier): void
    {
        $this->notifyThroughProcess($willBeNotified, $notificationData, $notifier);
    }

    /**
     * @param $willBeNotified
     * @param NotificationData $notificationData
     * @param NotifierInterface $notifier
     * @throws AppException
     * @throws \ReflectionException
     */
    private function notifyThroughProcess(
        $willBeNotified,
        NotificationData $notificationData,
        NotifierInterface $notifier
    ): void
    {
        $notification = $this->notificationsGenerator->generateNotificationForNotifier($notifier, $notificationData);

        // поддерживается ли нотификатор для данного пользователя ($willBeNotified) и уведомления ($notification)
        if ($notifier->supports($willBeNotified, $notification)) {
            // уведомляет пользователя
            $notifier->notify($willBeNotified, $notification);
        }
    }
}
