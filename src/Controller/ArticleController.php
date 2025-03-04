<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ArticleController extends AbstractController
{

    #[Route(path: "/articles", name: "app_user_articles")]
    public function allArticles(): Response
    {
        return $this->render('articles.html.twig');
    }

    #[Route(path: "/article/{id}", name: "app_user_article")]
    public function article($id): Response
    {
        return $this->render(
            'article.html.twig',
            [
                'numArticle' => $id
            ]
        );
    }
}