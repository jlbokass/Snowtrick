<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
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
     * @Route("/admin/article/new", name="add_article")
     *
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $user = $this->getUser();
            $article->setUser($user);
            $title = $article->getTitle();

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'the article '.$title.'was added'
            );

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('article_admin/new.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/admin/article/edit/{id}", name="edit_article", requirements={"id"="\d+"})
     *
     * @param Article $article
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function edit(Article $article, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();

            $manager->flush();

            $this->addFlash(
                'success',
                'Article added'
            );

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('article_admin/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/article/delete/{id}", name="delete_article", requirements={"id"="\d+"})
     *
     * @param Article $article
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Article $article, EntityManagerInterface $manager): Response
    {
        $manager->remove($article);
        $manager->flush();

        return $this->redirectToRoute('admin_article_index');
    }
}
