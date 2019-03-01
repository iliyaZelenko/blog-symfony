<?php

namespace App\Utils\Notify\Notifiers;

use App\Entity\Resources\SMSNotifiableInterface;
use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Notify\Notifications\SMSNotification;

class SMSNotifier implements NotifierInterface
{
    public function supports($willBeNotified, $notification): bool
    {
        return $willBeNotified instanceof SMSNotifiableInterface
            && $willBeNotified->getPhone()
            // && $willBeNotified->hasEnabledSMSNotifications()
            && $notification instanceof SMSNotification
        ;
    }

    /**
     * @param SMSNotifiableInterface $willBeNotified
     * @param SMSNotification $notification
     */
    public function notify($willBeNotified, $notification): void
    {
        $phone = $willBeNotified->getPhone();
        $message = $notification->getMessage();

        // TODO ->sendSMS($phone, $message);
    }
}
