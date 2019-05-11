<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryAdminController extends AbstractController
{
    /**
     * @Route("/admin/category/index", name="admin_category_index")
     */
    public function index(CategoryRepository $categoryRepository, Request $request)
    {
        $q = $request->query->get('q');

        $categories = $categoryRepository->findAllWithSearch($q);

        return $this->render('category_admin/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("admin/category/new", name="add_category")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(EntityManagerInterface $manager, Request $request)
    {
        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category = $form->getData();
            $category->setAuthor($this->getUser());
            dd($category);
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
}
