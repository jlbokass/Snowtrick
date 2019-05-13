<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryAdminController extends AbstractController
{
    /**
     * @Route("/admin/category/index", name="admin_category_index")
     *
     * @param CategoryRepository $categoryRepository
     * @param Request $request
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
     * @param Request $request
     * @return Response
     */
    public function new(EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $title = $category->getTitle();

            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'the category '.$title.'was added'
            );

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category_admin/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="edit_category", requirements={"id"="\d+"})
     *
     * @param Category $category
     * @param EntityManagerInterface $manager
     * @param Request $request
     * @return Response
     */
    public function edit(Category $category, EntityManagerInterface $manager, Request $request): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $manager->flush();

            $this->addFlash(
                'success',
                'Edit success'
            );

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('category_admin/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/delete/{id}", name="delete_category", requirements={"id"="\d+"})
     *
     * @param Category $category
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Category $category, EntityManagerInterface $manager): Response
    {

        $manager->remove($category);
        $manager->flush();

        return $this->redirectToRoute('admin_category_index');
    }
}
