<?php

namespace App\Entity\Resources;

interface SluggableInterface
{
    public function getSlugAttributes(): array;
}
