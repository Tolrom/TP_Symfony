<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use App\Repository\AccountRepository;
use App\Service\AccountService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class UserController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccountService $accountService
    ) {}

    #[Route(path: "/users", name: "app_user_list")]
    public function allAccounts(EntityManagerInterface $em): Response
    {
        try {
            $users = $this->accountService->getAll();
            $type = "success";
            $msg = "Accounts successfully fetched";
        } catch (\Exception $e) {
            $type = "danger";
            $msg = $e->getMessage();
        }
        $this->addFlash($type, $msg);
        return $this->render('account/users.html.twig', [
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
        $user = new Account();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        $type = "";
        $msg = "";
        // Form submitted
        if($form->isSubmitted() && $form->isValid()) {
            try {
                // Call to save method from the service
                $this->accountService->save($user);
                $type = "success";
                $msg = "Account successfully added";
            } 
            // Catch exceptions
            catch (\Exception $e) {
                $type = "danger";
                $msg = $e->getMessage();
            }
            
            $this->addFlash($type, $msg);
        }
        return $this->render('account/addaccount.html.twig',[
            'form' =>$form
        ]);
    }

    #[Route(path: "/account/{id}", name: "app_user_account")]
    public function showById(int $id): Response
    {
        $user = new Account();
        try {
            $user = $this->accountService->getById($id) ;
            $type = "success";
            $msg = "Account successfully fetched";
        } catch (\Exception $e) {
            $type = "danger";
            $msg = $e->getMessage();
        }
        
        $this->addFlash($type, $msg);
        return $this->render(
            'account/user.html.twig',
            [
                'user' => $user
            ]
        );
    }
}