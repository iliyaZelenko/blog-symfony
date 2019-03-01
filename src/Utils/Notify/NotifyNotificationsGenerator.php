<?php

namespace App\Utils\Notify;

use App\Exceptions\AppException;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Contracts\Notify\NotifyNotificationsGeneratorInterface;
use App\Utils\Notify\Notifications\NotificationData;
use App\Utils\Notify\Notifiers\NotificationsGenerators\EmailNotifierNotificationGenerator;
use App\Utils\Contracts\Notify\Notifiers\NotificationsGenerators\NotificationGeneratorInterface;
use App\Utils\Notify\Notifiers\NotificationsGenerators\SMSNotifierNotificationGenerator;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class NotifyNotificationsGenerator implements NotifyNotificationsGeneratorInterface, ServiceSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $locator;

    public static function getSubscribedServices(): array
    {
        return [
            EmailNotifierNotificationGenerator::class,
            SMSNotifierNotificationGenerator::class
        ];
    }

    public function __construct(
        ContainerInterface $locator
    )
    {
        $this->locator = $locator;
    }

    /**
     * Использую сервис локатор с динамическим именем класса чтобы класс был открыт для расширения закрыт для изменения.
     * Способ прочитал в книге.
     *
     * @inheritdoc
     */
    public function generateNotificationForNotifier(
        NotifierInterface $notifier,
        NotificationData $notificationData
    )
    {
        // это производительный вариант получения: https://coderwall.com/p/cpxxxw/php-get-class-name-without-namespace
        $notifierClass = (new \ReflectionClass($notifier))->getShortName();
        $notifierNotificationGeneratorClass = 'App\Utils\Notify\Notifiers\NotificationsGenerators\\'
            . $notifierClass . 'NotificationGenerator';

        if ($this->locator->has($notifierNotificationGeneratorClass)) {
            /** @var NotificationGeneratorInterface $generator */
            $generator = $this->locator->get($notifierNotificationGeneratorClass);

            return $generator->generateNotificationForNotifier(
                $notificationData,
                $notifier
            );
        }

        throw new AppException('Notification generator class not found for a ' . $notifierClass . ' class ('
            . $notifierNotificationGeneratorClass . ')!');
    }
}
