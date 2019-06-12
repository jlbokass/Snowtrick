<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     *
     * @param ArticleRepository $articleRepository
     * @param ImageRepository $imageRepository
     *
     * @return Response
     */
    public function homepage(ArticleRepository $articleRepository, ImageRepository $imageRepository): Response
    {
        /** @var Article $articles */
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC'], 8, 0);

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/article/show/{id}", name="article_show")
     *
     * @param Article                $article
     * @param Request                $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function show(
        Article $article,
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Comment $comment */
            $comment = $form->getData();
            $comment->setArticle($article);
            $comment->setUser($this->getUser());

            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('article_show', [
                'id' => $article->getId(),
            ]);
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'formComment' => $form->createView(),
        ]);
    }

    /**
     * Get the 15 next tricks in the database and create a Twig file with them that will be displayed via Javascript.
     *
     * @Route("/{start}", name="loadMoreTricks", requirements={"start": "\d+"})
     */
    public function loadMoreTricks(ArticleRepository $articleRepository, $start = 4)
    {
        // Get 15 tricks from the start position
        $article = $articleRepository->findBy([], ['createdAt' => 'DESC'], 4, $start);

        return $this->render('article/loadMoreArticle.html.twig', [
            'articles' => $article,
        ]);
    }
}
