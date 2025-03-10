<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $em
    ) {}

    #[Route(path: "/articles", name: "app_user_articles")]
    public function allArticles(): Response
    {
        $articles = $this->em->getRepository(Article::class)->findAll();
        return $this->render('articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route(path: "/article/{id}", name: "app_user_article")]
    public function article($id): Response
    {
        $article = $this->em->getRepository(Article::class)->find($id) ;
        return $this->render(
            'article.html.twig',
            [
                'article' => $article
            ]
        );
    }
}