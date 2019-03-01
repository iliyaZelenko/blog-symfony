<?php

namespace App\Controllers\RESTful\Auth;

use App\Entity\Factories\UserFactoryInterface;
use App\Entity\User;
use App\Entity\UserInterface;
use App\Exceptions\AppException;
use App\Form\DataObjects\User\UserCreationData;
use App\Repository\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    /**
     * @var string
     */
    private $tokenTTL;

    /**
     * @var \App\RESTResources\User\UserResource
     */
    private $resource;
    /**
     * @var JWTTokenManagerInterface
     */
    private $JWTManager;

    public function __construct(
        string $tokenTTL,
        \App\RESTResources\User\UserResource $resource,
        JWTTokenManagerInterface $JWTManager
    )
    {
        $this->tokenTTL = $tokenTTL;
        $this->resource = $resource;
        $this->JWTManager = $JWTManager;
    }

    public function register(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UserFactoryInterface $userFactory
    )
    {
        [
            'username' => $username,
            'password' => $password,
            'email' => $email
        ] = $request->request->all();

        $userData = (new UserCreationData())
            ->setUsername($username)
            ->setPlainPassword($password)
            ->setEmail($email)
        ;

        $errors = $validator->validate($userData);

        try {
            $createdUser = $userFactory->createNew($userData);
        } catch (AppException $e) {
            $error = new ConstraintViolation(
                $e->getMessage(), '', [], $userData, '[user factory]', null
            );
            $errors[] = $error;
            /* TODO create error */
            // $message = $e->getMessage()
//             $errors->add($error);
        }

        if (count($errors)) {
            $errorMessages = [];
            /** @var ConstraintViolation $e */
            foreach ($errors as $e) {
                $errorMessages[$e->getPropertyPath()] = $e->getMessage();
            }
            // Так не работает
            // $errorMessages = array_map(function ($e) {
            //    return $e->getMessage();
            // }, $errors);

            // TODO Сделать централизованную систему ошибок
            return new JsonResponse([
                'errors' => $errorMessages
            ]);
        }

        $em->persist($createdUser);
        $em->flush();

        return new JsonResponse(
            $this->getAuthResponseData($createdUser)
        );
    }

    public function signIn(
        Request $request,
        UserRepositoryInterface $userRepo,
        UserPasswordEncoderInterface $encoder
    ): JsonResponse
    {
        [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ] = $request->request->all();

        // похоже в симфони сделать пользовательский вход довольно тяжело
        $userByUsername = $userRepo->findOneBy([
            'username' => $username
        ]);
        $userByEmail = $userRepo->findOneBy([
            'email' => $email
        ]);
        $user = $userByUsername ?? $userByEmail;

        if (!$user) {
            // TODO Сделать централизованную систему ошибок
            return new JsonResponse([
                'error' => 'No user found by ' . ($username ? 'username' : 'email') . '.',
                Response::HTTP_UNAUTHORIZED
            ]);
        }

        if(!$encoder->isPasswordValid($user, $password)) {
            return new JsonResponse(
                'Password is not valid.',
                Response::HTTP_UNAUTHORIZED
            );
        }

        /** @var UserInterface $user */
        return new JsonResponse(
            $this->getAuthResponseData($user)
        );
    }

    // Тестовая endpoint
    public function api(): JsonResponse
    {
        return new JsonResponse(
            sprintf('Logged in as %s', $this->getUser()->getUsername())
        );
    }

    public function user(): JsonResponse
    {
        return new JsonResponse([
            'user' => $this->resource->toArray(
                $this->getUser()
            )
        ]);
    }

    private function getAuthResponseData (UserInterface $user): array
    {
        $token = $this->JWTManager->create($user);

        return [
            'message' => sprintf('User %s successfully created.', $user->getUsername()),
            'user' => $this->resource->toArray($user),
            'tokenInfo' => [
                'accessToken' => $token,
                // timestamp
                'expiresIn' => $this->getExpiredAt($this->tokenTTL),
                // timestamp TODO
                'refreshTokenExpiresIn' => ''
            ]
        ];
    }

    // Проще варианта походу нет, не до конца понятно как декодить токен (я видел метод), вытащил бы от туда exp
    private function getExpiredAt($ttl): int
    {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));
        $expiredAt = $now->add(
            new \DateInterval('PT' . $ttl . 'S')
        );

        return $expiredAt->getTimestamp();
    }
}
