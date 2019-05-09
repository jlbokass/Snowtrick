<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article")
     */
    public function new(EntityManagerInterface $manager)
    {
       die('todo');

        return new Response(sprintf(
            'Hiya! new Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));

    }
}
