<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Form\EmailToResetPasswordType;
use App\Repository\UserRepository;
use App\Service\ResetPasswordSender;
use App\Service\TokenSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordAdminController extends AbstractController
{
    /**
     * @Route("/forgot/password", name="forgot_password")
     *
     * @param UserRepository         $userRepository
     * @param EntityManagerInterface $manager
     * @param Request                $request
     * @param ResetPasswordSender            $sender
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

    public function resetPassword()
    {
        // ...
    }
}
