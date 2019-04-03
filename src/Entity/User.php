<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Entity\Resources\CreatedUpdatedTrait;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity({"username", "email"})
 */
class User implements UserInterface
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    public function __construct(string $username, string $email)
    {
        $this->isActive = true;
        $this->username = $username;
        $this->email = $email;
    }

    /* Getters / Setters */

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): UserInterface
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): UserInterface
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): UserInterface
    {
        $this->email = $email;

        return $this;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /* Other */

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        // например, чтобы не палить пароль (у меня нет plainPassword)
        // $this->plainPassword = null;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized): void
    {
        // TODO был list, проверить работает ли
        [
            $this->id,
            $this->username,
            $this->password,
        ] = unserialize($serialized, [
            'allowed_classes' => false
        ]);
    }
}
