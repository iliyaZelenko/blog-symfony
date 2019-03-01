<?php

namespace App\EventListeners;

use App\Entity\Resources\CreatedUpdatedInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntitiesCreatedUpdatedListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof CreatedUpdatedInterface) {
            $entity->setCreatedAt($this->getNowUTC());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof CreatedUpdatedInterface) {
            $entity->setUpdatedAt($this->getNowUTC());
        }
    }

    private function getNowUTC(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(
            'now',
            new \DateTimeZone('UTC')
        );
    }
}
