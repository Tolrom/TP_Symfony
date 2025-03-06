<?php

namespace App\Controller;

use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    #[Route(path: "/users", name: "app_user_list")]
    public function allAccounts(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(Account::class)->findAll();
        return $this->render('users.html.twig', [
            'users' => $users
        ]);
    }

    #[Route(path: "/register", name: "app_user_register")]
    public function register(): Response
    {
        return $this->render('register.html.twig');
    }

    #[Route(path: "/login", name: "app_user_login")]
    public function login(): Response
    {
        return $this->render('login.html.twig');
    }
}