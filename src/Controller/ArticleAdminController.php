<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/index", name="admin_article_index")
     *
     * @param ArticleRepository $articleRepository
     *
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, Request $request)
    {
        $q = $request->query->get('q');

        $articles = $articleRepository->findAllWithSearch($q);

        return $this->render('article_admin/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/article/new")
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
