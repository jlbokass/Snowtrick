<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(ArticleRepository $articleRepository)
    {
        /** @var Article $articles */
        $articles = $articleRepository->findAllPublishedOrderedByNewest();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/articles", name="app_articles")
     */
    public function article(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAllPublishedOrderedByNewest();

        return $this->render('article/article.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article)
    {
        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on all-asteroid diet!',
            'I like bacon too! Buy some from my site! backinsomebacon.com'
        ];

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
        ]);
    }
}
