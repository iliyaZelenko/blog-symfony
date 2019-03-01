<?php

namespace App\Entity\Factories;

use App\Entity\User;
use App\Entity\UserInterface;
use App\Exceptions\AppException;
use App\Form\DataObjects\User\UserCreationData;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Factory method pattern
 */
class UserFactory implements UserFactoryInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepo;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepositoryInterface $userRepo
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepo = $userRepo;
    }

    /**
     * @inheritdoc
     */
    public function createNew(UserCreationData $data): UserInterface
    {
        $username = $data->getUsername();
        $email = $data->getEmail();
        $duplicateErrorMsg = [];

        if ($this->userRepo->findOneBy([
            'username' => $username
        ])) {
            $duplicateErrorMsg[] = "A user with this username ($username) already exists.";
        }
        if ($this->userRepo->findOneBy([
            'email' => $email
        ])) {
            $duplicateErrorMsg[] = "A user with this email ($email) already exists.";
        }
        if (count($duplicateErrorMsg)) {
            throw new AppException(
                implode($duplicateErrorMsg, ' '),
                409
            );
        }

        $user = new User(
            $data->getUsername(),
            $data->getEmail()
        );

        // encode the plain password
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $data->getPlainPassword()
            )
        );


        return $user;
    }
}
