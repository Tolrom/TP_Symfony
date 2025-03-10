<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em
    ) {}

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
        return $this->render('account/register.html.twig');
    }

    #[Route(path: "/login", name: "app_user_login")]
    public function login(): Response
    {
        return $this->render('account/login.html.twig');
    }

    #[Route('/account/add', name:'app_account_add')]
    public function addAccount(Request $request): Response
    {   
        
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = "";
        $status ="";
        if($form->isSubmitted()){
            try {
                $account->setRole('ROLE_USER');
                $this->em->persist($account);
                $this->em->flush();
                $msg = "Account successfuly added";
                $status = "success";
            } catch (\Exception $e) {
                $msg ="This email is already registered";
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('account/addaccount.html.twig',
        [
            'form'=> $form
        ]);
    }
}