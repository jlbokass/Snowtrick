<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\EmailToResetPasswordType;
use App\Form\ProfileType;
use App\Repository\UserRepository;
use App\Service\TokenSender;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController.
 */
class ProfileController extends AbstractController
{
    /**
     *  @IsGranted("ROLE_USER")
     * @Route("/profile/show", name="profile_show")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig'
        );
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     *
     * @param Request                $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager): Response
    {
        $profileForm = $this->createForm(ProfileType::class, $this->getUser());
        $profileForm->handleRequest($request);

        if ($profileForm->isSubmitted() && $profileForm->isValid()) {
            $manager->flush();

            return $this->redirectToRoute('profile_show');
        }

        return $this->render('profile/edit.html.twig', [
            'profileForm' => $profileForm->createView(),
        ]);
    }

    /**
     * @Route("/forgot/password", name="forgot_password")
     *
     * @param UserRepository         $userRepository
     * @param EntityManagerInterface $manager
     * @param Request                $request
     * @param TokenSender            $sender
     *
     * @return Response
     */
    public function resetPassword(
        UserRepository $userRepository,
        EntityManagerInterface $manager,
        Request $request,
        TokenSender $sender): Response
    {
        $emailToResetForm = $this->createForm(EmailToResetPasswordType::class);
        $emailToResetForm->handleRequest($request);

        if ($emailToResetForm->isSubmitted() && $emailToResetForm->isValid()) {
            $email = $emailToResetForm->get('email')->getData();

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

        return $this->render('profile/forgotPassword.html.twig', [
            'emailToResetForm' => $emailToResetForm->createView(),
        ]);
    }
}
