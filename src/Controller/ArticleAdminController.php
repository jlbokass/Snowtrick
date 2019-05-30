<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function new(EntityManagerInterface $manager, Request $request, UploaderHelper $uploaderHelper): Response
    {
        $articleForm = $this->createForm(ArticleType::class);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            $article = $articleForm->getData();

            $attachments = $article->getImages();

            if ($attachments) {

                foreach ($attachments as $attachment) {

                    $file = $attachment->getFile();
                    $newFilename = $uploaderHelper->uploadArticleImage($file);
                    $attachment->setImageFilename($newFilename);
                }
            }

            $user = $this->getUser();
            $article->setUser($user);

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('article_admin/new.html.twig', [
            'articleForm' => $articleForm->createView(),
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
    public function edit(Article $article, EntityManagerInterface $manager, Request $request, UploaderHelper $uploaderHelper): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $article);

        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

        /** @var UploadedFile $uploadedFile */
            $uploadedFile = $articleForm['imageFile']->getData();

            if ($uploadedFile) {

                $newFilename = $uploaderHelper->uploadArticleImage($uploadedFile);

                $article->setImageFilename($newFilename);
            }

            $manager->flush();

            return $this->redirectToRoute('edit_article', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('article_admin/edit.html.twig', [
            'article' => $article,
            'articleForm' => $articleForm->createView()
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
        $this->denyAccessUnlessGranted('DELETE', $article);

        $manager->remove($article);
        $manager->flush();

        return $this->redirectToRoute('app_homepage');
    }

    /**
     * @Route("/admin/article/delete/image/{id}",
     *     name="delete_image",
     *     methods={"POST"},
     *     condition="request.headers.get('X-Requested-With') matches '/XMLHttpRequest/i'")
     *
     * @param Image $image
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function deleteImage(Image $image, EntityManagerInterface $manager)
    {
        $manager->remove($image);
        $manager->flush();

        return new JsonResponse();
    }
}
