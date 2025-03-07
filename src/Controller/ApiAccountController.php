<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class ApiAccountController extends AbstractController
{

    public function __construct(
        private AccountRepository $accountRepository,
        private ArticleRepository $articleRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/accounts', name: 'api_account_get')]
    public function getAccounts(): Response
    {
        return $this->json(
            $this->accountRepository->findAll(),
            200,
            [],
            ['groups' => 'account:read']

        );
    }

    #[Route('api/account/add', name: 'api_account_add', methods: ['POST'])]
    public function addAccount(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $json = $request->getContent();
        $account = $this->serializer->deserialize($json, Account::class, 'json');
        if ($account->getFirstname() && $account->getLastname() && $account->getEmail() && $account->getPassword() && $account->getRole()) {
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                $account->setPassword($hasher->hashPassword($account, $account->getPassword()));
                $this->em->persist($account);
                $this->em->flush();
                $code = 201;
            } else {
                $account = "Account already exists";
                $code = 400;
            }
        }
        //Sinon les champs ne spont pas remplis
        else {
            $account = "Veuillez remplir tous les champs";
            $code = 400;
        }
        return $this->json($account, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }

    #[Route('api/account/update', name: 'api_account_update', methods: ['PUT'])]
    public function updateAccount(Request $request): Response
    {
        $json = $request->getContent();
        $account = $this->serializer->deserialize($json, Account::class, 'json');
        $oldAcc = $this->accountRepository->findOneBy(["email" => $account->getEmail()]);
        if ($oldAcc) {
            $oldAcc->setFirstname($account->getFirstname());
            $oldAcc->setLastname($account->getLastname());
            $this->em->persist($oldAcc);
            $this->em->flush();
            $code = 204;
        } else {
            $oldAcc = "Account doesn't exist";
            $code = 400;
        }
        return $this->json($oldAcc, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }

    #[Route('api/account/update-password', name: 'api_account_update_password', methods: ['PATCH'])]
    public function updateAccountPassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password']) || empty($data['email']) || empty($data['password'])) {
            return $this->json(["error" => "Email and password are required"], Response::HTTP_BAD_REQUEST, [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ], []);
        }

        $account = $this->accountRepository->findOneBy(["email" => $data['email']]);

        if (!$account) {
            return $this->json(["error" => "Account not found"], Response::HTTP_NOT_FOUND, [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ], []);
        }

        $hashedPassword = $hasher->hashPassword($account, $data['password']);
        $account->setPassword($hashedPassword);
        $this->em->flush();

        return $this->json(["message" => "Password updated successfully"], Response::HTTP_OK, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }


    #[Route('api/account/delete', name: 'api_account_delete', methods: ['DELETE'])]
    public function deleteAccount(Request $request): Response
    {
        $id = $request->toArray()['id'];
        $oldAcc = $this->accountRepository->find($id);
        if ($oldAcc) {
            $articles = $this->articleRepository->findBy(["author" => $oldAcc]);
            foreach ($articles as $article) {
                $article->setAuthor(null);
                $this->em->persist($article);
            }
            $this->em->remove($oldAcc);
            $this->em->flush();
            $msg = "The account has been deleted";
            $code = 204;
        } else {
            $msg = "Account not found";
            $code = 404;
        }
        return $this->json($msg, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }
}