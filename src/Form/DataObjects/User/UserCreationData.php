<?php

namespace App\Form\DataObjects\User;

use Symfony\Component\Validator\Constraints as Assert;

final class UserCreationData
{
    /**
     * Допускаются русские символы (почему никнейм обязательно должен быть на английском?).
     *
     * @Assert\Regex("/^[а-яА-Яa-zA-ZЁё][а-яА-Яa-zA-Z0-9Ёё]*?([-_.][а-яА-Яa-zA-Z0-9Ёё]+){0,3}$/u")
     */
    private $username;

    /**
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    private $email;

    // TODO если нужен перевод, то передавать message="author.name.not_blank"
    // https://symfony.com/doc/current/validation/translations.html
    /**
     * @Assert\NotBlank(message="Please enter a password")
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "Your password should be at least {{ limit }} characters",
     *      maxMessage = "Your password cannot be longer than {{ limit }} characters"
     * )
     */
    private $plainPassword;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return UserCreationData
     */
    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return UserCreationData
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     * @return UserCreationData
     */
    public function setPlainPassword($plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
