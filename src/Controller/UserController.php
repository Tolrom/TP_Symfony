<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{

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