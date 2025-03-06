<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiArticleController extends AbstractController
{

    public function __construct(
        private ArticleRepository $articleRepository
    ) {
    }

    #[Route('/api/articles', name: 'app_article_get')]
    public function getCategories(): Response
    {
        return $this->json(
            $this->articleRepository->findAll(),
            200,
            [],
            ['groups' => 'article:read']

        );

    }
}