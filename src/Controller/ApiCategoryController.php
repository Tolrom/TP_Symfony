<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiCategoryController extends AbstractController
{

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
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

    #[Route('api/category', name: 'app_category_add', methods: ['POST'])]
    public function addCategory(Request $request): Response
    {
        $request = $request->getContent();
        // dd($request);
        $category = $this->serializer->deserialize($request, Category::class, 'json');
        // dd($category);

        if (!$this->categoryRepository->findOneBy(["name" => $category->getName()])) {
            $this->em->persist($category);
            $this->em->flush();
            $code = 201;
        } else {
            $category = "Category already exists";
            $code = 400;
        }
        return $this->json($category, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }

}