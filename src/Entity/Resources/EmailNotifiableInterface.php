<?php

namespace App\Entity\Resources;

interface EmailNotifiableInterface
{
    public function getEmail(): ?string;
}
