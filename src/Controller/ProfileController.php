<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/show", name="profile_show")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig'
        );
    }

    public function edit()
    {

    }

    public function delete()
    {

    }
}
