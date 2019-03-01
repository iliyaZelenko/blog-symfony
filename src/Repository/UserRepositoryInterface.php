<?php

namespace App\Repository;

use App\Entity\UserInterface;

interface UserRepositoryInterface extends BaseRepositroyInterface
{
    /**
     * Get first entity
     *
     * @return UserInterface|null
     */
    public function getFirst(): ?UserInterface;
}
