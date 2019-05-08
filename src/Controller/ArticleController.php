<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug)
    {
        return $this->render('article/show.html.twig', [
            'slug' => $slug
        ]);
    }
}
