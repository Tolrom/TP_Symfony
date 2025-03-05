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

    #[Route(path: "/articles", name: "app_user_articles")]
    public function allArticles(EntityManagerInterface $em): Response
    {
        $articles = $em->getRepository(Article::class)->findAll();
        return $this->render('articles.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route(path: "/article/{id}", name: "app_user_article")]
    public function article(EntityManagerInterface $em, $id): Response
    {
        $article = $em->getRepository(Article::class)->find($id) ;
        return $this->render(
            'article.html.twig',
            [
                'article' => $article
            ]
        );
    }
}