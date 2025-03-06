<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiCategoryController extends AbstractController
{

    public function __construct(
        private CategoryRepository $categoryRepository
    ) {
    }

    #[Route('/api/categories', name: 'app_category_get')]
    public function getCategories(): Response
    {
        return $this->json(
            $this->categoryRepository->findAll(),
            200,
            [],
            ['groups' => 'category:read']

        );

    }
}