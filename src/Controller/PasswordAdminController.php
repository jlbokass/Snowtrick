<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\EmailToResetPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use App\Service\ResetPasswordSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordAdminController extends AbstractController
{
    /**
     * @Route("/forgot/password", name="forgot_password")
     *
     * @param UserRepository         $userRepository
     * @param EntityManagerInterface $manager
     * @param Request                $request
     * @param ResetPasswordSender    $sender
     *
     * @return Response
     */
    public function sendTokenToResetPassword(
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request,
        ResetPasswordSender $sender): Response
    {
        $validEmailToResetForm = $this->createForm(EmailToResetPasswordType::class);
        $validEmailToResetForm->handleRequest($request);

        if ($validEmailToResetForm->isSubmitted() && $validEmailToResetForm->isValid()) {
            $email = $validEmailToResetForm->get('email')->getData();

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                $this->addFlash(
                    'info',
                    'This email do not exists'
                );

                return $this->redirectToRoute('app_homepage');
            }

            $token = new ApiToken($user);
            $sender->sendToken($user, $token);
            $manager->persist($token);
            $manager->flush();

            $this->addFlash(
                'info',
                'Pleas check your email'
            );

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('password_admin/forgotPassword.html.twig', [
            'emailToResetForm' => $validEmailToResetForm->createView(),
        ]);
    }

    /**
     * @Route("/confirmation/reset-password/{token}", name="reset_token_validation")
     *
     * @param $token
     * @param ApiTokenRepository     $repository
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function validateResetToken(
        $token,
        ApiTokenRepository $repository,
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager
    ): Response {
        $token = $repository->findOneBy(['token' => $token]);

        if (!$token->getExpiresAt()) {
            return $this->render('password_admin/token_expired.html.twig');
        }

        /** @var User $user */
        $user = $token->getUser();

        $form = $this->createForm(ResetPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $form->get('password')->getData()));

            $manager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_admin/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
