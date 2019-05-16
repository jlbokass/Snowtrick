<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(ArticleRepository $articleRepository)
    {
        /** @var Article $articles */
        $articles = $articleRepository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setArticle($article);
            $comment->setUser($this->getUser());
            //dd($comment);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('article_show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'formComment' => $form->createView(),
        ]);
    }
}
