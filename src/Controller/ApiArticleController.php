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
    public function getArticles(): Response
    {
        return $this->json(
            $this->articleRepository->findAll(),
            200,
            [],
            ['groups' => 'articles:read']

        );

    }
    #[Route('/api/article/{id}', name: 'app_article_getOne')]
    public function getArticleById(int $id): Response
    {
        $article = $this->articleRepository->find($id);
        $code = 200;
        if (!$article) {
            $article = "L'article n'existe pas";
            $code = 404;
        }
        return $this->json(
            $this->articleRepository->find($id),
            $code,
            [],
            ['groups' => ['articles:read', 'article:read']]

        );

    }
}