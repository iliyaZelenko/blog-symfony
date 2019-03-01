<?php

namespace App\Utils\Contracts\Notify;

use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;

interface NotifyChainInterface
{
    public function addNotifier(NotifierInterface $notifier): void;

    /**
     * @return NotifierInterface[]
     */
    public function getNotifiers(): array;

    /**
     * @param array $notifiers
     */
    public function setNotifiers(array $notifiers): void;
}
