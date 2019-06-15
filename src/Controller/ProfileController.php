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
     * @IsGranted("ROLE_USER")
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
}
