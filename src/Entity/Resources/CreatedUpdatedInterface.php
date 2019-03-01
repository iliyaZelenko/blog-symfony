<?php

namespace App\Entity\Resources;

interface CreatedUpdatedInterface
{
    public function setCreatedAt(\DateTimeImmutable $createdAt);
    public function setUpdatedAt(\DateTimeImmutable $updatedAt);
}
