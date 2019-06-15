<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * Class CategoryAdminController.
 */
class CategoryAdminController extends AbstractController
{
    /**
     * @Route("/admin/category/index", name="admin_category_index")
     *
     * @param CategoryRepository $categoryRepository
     * @param Request            $request
     *
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {
        $q = $request->query->get('q');

        $categories = $categoryRepository->findAllPublishedOrderedByNewest();

        return $this->render('category_admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("admin/category/new", name="add_category")
     *
     * @param EntityManagerInterface $manager
     * @param Request                $request
     *
     * @return Response
     */
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $categoryForm = $this->createForm(CategoryType::class);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $category = $categoryForm->getData();

            $user = $this->getUser();

            $category->setUser($user);
            $manager->persist($category);
            $manager->flush();

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category_admin/new.html.twig', [
            'categoryForm' => $categoryForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="edit_category", requirements={"id"="\d+"})
     *
     * @param Category               $category
     * @param EntityManagerInterface $manager
     * @param Request                $request
     *
     * @return Response
     */
    public function edit(Category $category, EntityManagerInterface $manager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('EDIT', $category);

        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $manager->flush();

            $this->addFlash(
                'success',
                'Edit success'
            );

            return $this->redirectToRoute('app_homepage');
        }

        return $this->render('category_admin/edit.html.twig', [
            'category' => $category,
            'form' => $categoryForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="delete_category", requirements={"id"="\d+"})
     *
     * @param Category               $category
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function delete(Category $category, EntityManagerInterface $manager): Response
    {
        $this->denyAccessUnlessGranted('DELETE', $category);

        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute('admin_category_index');
    }
}
