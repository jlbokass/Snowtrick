<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\ApiTokenRepository;
use App\Service\TokenSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
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

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/registration", name="app_register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $manager
     * @param TokenSender $sender
     * @return Response
     */
    public function registration(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $manager,
        TokenSender $sender): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);

            $token = new ApiToken($user);
            $sender->sendToken($user, $token);

            $manager->persist($user);
            $manager->persist($token);
            $manager->flush();

            $this->addFlash(
                'success',
                'Please check your email'
            );

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/confirmation/{token}", name="token_validation")
     *
     * @param $token
     * @param ApiTokenRepository $repository
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function validateToken($token,ApiTokenRepository $repository, EntityManagerInterface $manager): Response
    {
        $token = $repository->findOneBy(['token' => $token]);

        $user = $token->getUser();

        if ($user->getIsEnable()) {

            return $this->render('registration/alreadyRegister.html.twig');
        }

        if ($token->getExpiresAt()) {

            $user->setIsEnable(true);

            $manager->flush();

            $this->addFlash(
                'success',
                'you registered, please check your email'
            );

            return $this->redirectToRoute('app_homepage');

        }

        $manager->remove($token);
        $manager->flush();

        $this->addFlash(
            'notice',
            'date expired'
        );

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }
}
