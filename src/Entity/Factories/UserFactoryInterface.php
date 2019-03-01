<?php

namespace App\Entity\Factories;

use App\Entity\UserInterface;
use App\Exceptions\AppException;
use App\Form\DataObjects\User\UserCreationData;

/**
 * Factory method pattern
 */
interface UserFactoryInterface
{
    /**
     * @param UserCreationData $data
     * @return UserInterface
     * @throws AppException
     */
    public function createNew(UserCreationData $data): UserInterface;
}
