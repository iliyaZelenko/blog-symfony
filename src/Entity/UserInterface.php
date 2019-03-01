<?php

namespace App\Entity;

use App\Entity\Resources\CreatedUpdatedInterface;
use App\Entity\Resources\EmailNotifiableInterface;
use Symfony\Component\Security\Core\User\UserInterface as AuthUserInterface;

interface UserInterface extends
    \Serializable,
    AuthUserInterface,
    CreatedUpdatedInterface,
    EmailNotifiableInterface
{
    // Это вызывало ошибку Declaration of Proxies\__CG__\App\Entity\User must be compatible with App\Entity\UserInterface
    // public function __construct(string $username, string $email);

    /* Getters / Setters */

    public function getId();

    public function getUsername(): string;

    public function setUsername(string $username): self;

    public function getPassword(): string;

    public function setPassword($password): self;

    public function getEmail(): ?string;

    public function setEmail($email): self;
}
