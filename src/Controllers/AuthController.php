<?php

namespace App\Controllers;

use App\Entity\Factories\UserFactoryInterface;
use App\Exceptions\AppException;
use App\Utils\Contracts\Recaptcha\RecaptchaInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\DataObjects\User\UserCreationData;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class AuthController extends AbstractController
{
    /**
     * Sign in
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * Registration
     *
     * @param Request $request
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param RecaptchaInterface $recaptcha
     * @param UserFactoryInterface $userFactory
     * @return Response
     */
    public function register(
        Request $request,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        RecaptchaInterface $recaptcha,
        UserFactoryInterface $userFactory,
        EntityManagerInterface $entityManager
    ): Response
    {
        $captchaResponse = $request->get('g-recaptcha-response');
        $form = $this->createForm(
            RegistrationFormType::class,
            new UserCreationData()
        );

        $form->handleRequest($request);

        // по моему это гениально
        try {
            if (!$form->isSubmitted() || !$form->isValid()) {
                throw new AppException(null, 422);
            }
            // TODO Сделать совместимость с формой
            if (!$recaptcha->check($captchaResponse)) {
                throw new AppException('Captcha check failed.', 422);
            }
            // это тоже кидает исключение
            $createdUser = $userFactory->createNew(
                $form->getData()
            );
        } catch (AppException $e) {
            if ($message = $e->getMessage()) {
                // добавляет глобальную ошибку связанную с формой (не имеет поля)
                $form->addError(
                    new FormError($message)
                );
            }

            return $this->render('auth/register.html.twig', [
                'registrationForm' => $form->createView(),
            ]);
        }

        $entityManager->persist($createdUser);
        $entityManager->flush();


        return $guardHandler->authenticateUserAndHandleSuccess(
            $createdUser,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );
    }
}
