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
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(['ROLE_USER']);

            $token = new ApiToken($user);
            $sender->sendToken($user, $token);

            $manager->persist($user);
            $manager->persist($token);
            $manager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/confirmation/{token}", name="token_validation")
     *
     * @param ApiToken $token
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function validateToken(
        $token,
        ApiTokenRepository $apiTokenRepository,
        EntityManagerInterface $manager)
    {
        $token = $apiTokenRepository->findOneBy(['token' => $token]);

        //dd($token);

        if(null === $token) {
            throw new NotFoundHttpException();
        }

        $user = $token->getUser();

        if ($token->getExpiresAt()) {

            $user->setIsEnable(true);

            $manager->flush();

            $this->addFlash(
                'success',
                'you registered, please sign in'
            );

            return $this->redirectToRoute('app_login');

        }

        $manager->remove($user);
        $manager->remove($token);
        $manager->flush();

        $this->addFlash(
            'notice',
            'date experires'
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
