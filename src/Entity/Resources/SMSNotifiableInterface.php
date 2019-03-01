<?php

namespace App\Entity\Resources;

interface SMSNotifiableInterface
{
    public function getPhone(): ?string;
}
