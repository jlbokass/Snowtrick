<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
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

        return $this->render('category_admin/index2.html.twig', [
            'categories' => $categories,
        ]);
    }
}
