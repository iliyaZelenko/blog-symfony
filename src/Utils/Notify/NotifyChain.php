<?php

namespace App\Utils\Notify;

use App\Utils\Contracts\Notify\Notifiers\NotifierInterface;
use App\Utils\Contracts\Notify\NotifyChainInterface;

class NotifyChain implements NotifyChainInterface
{
    private $notifiers = [];

    public function addNotifier(NotifierInterface $notifier): void
    {
        $this->notifiers[] = $notifier;
    }

    /**
     * @return NotifierInterface[]
     */
    public function getNotifiers(): array
    {
        return $this->notifiers;
    }

    /**
     * @param array $notifiers
     */
    public function setNotifiers(array $notifiers): void
    {
        $this->notifiers = $notifiers;
    }
}
