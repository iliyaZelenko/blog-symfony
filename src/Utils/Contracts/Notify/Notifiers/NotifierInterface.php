<?php

namespace App\Utils\Contracts\Notify\Notifiers;

interface NotifierInterface
{
    public function supports($willBeNotified, $notification): bool;

    public function notify($willBeNotified, $notification);
}
