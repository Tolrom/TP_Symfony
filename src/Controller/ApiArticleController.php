<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\AccountRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiArticleController extends AbstractController
{

    public function __construct(
        private ArticleRepository $articleRepository,
        private AccountRepository $accountRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
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

    #[Route('api/addarticle', name: 'app_article_add', methods: ['POST'])]
    public function addArticle(Request $request): Response
    {
        $json = $request->getContent();
        // dd($request);
        $article = $this->serializer->deserialize($json, Article::class, 'json');
        if ($article->getTitle() && $article->getContent() && $article->getAuthor()) {
            $article->setAuthor($this->accountRepository->findOneBy(["email" => $article->getAuthor()->getEmail()]));
            foreach ($article->getCategories() as $cat) {
                $catName = $cat->getName();
                $article->removeCategory($cat);
                $article->addCategory($this->categoryRepository->findOneBy(["name" => $catName]));
            }
            $article->setCreatedAt(new DateTimeImmutable());
            // dd($article);
        }

        if (
            !$this->articleRepository->findOneBy([
                "title" => $article->getTitle(),
                "content" => $article->getContent()
            ])
        ) {
            $this->em->persist($article);
            $this->em->flush();
            $code = 201;
        } else {
            $article = "Article already exists";
            $code = 400;
        }
        return $this->json($article, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }
}