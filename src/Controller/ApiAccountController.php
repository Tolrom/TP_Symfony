<?php

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiAccountController extends AbstractController
{

    public function __construct(
        private AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ) {
    }

    #[Route('/api/accounts', name: 'app_account_get')]
    public function getAccounts(): Response
    {
        return $this->json(
            $this->accountRepository->findAll(),
            200,
            [],
            ['groups' => 'account:read']

        );
    }

    #[Route('api/addaccount', name: 'app_account_add', methods: ['POST'])]
    public function addAccount(Request $request): Response
    {
        $request = $request->getContent();
        // dd($request);
        $account = $this->serializer->deserialize($request, Account::class, 'json');
        // dd($account);c

        if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
            $this->em->persist($account);
            $this->em->flush();
            $code = 201;
        } else {
            $account = "Account already exists";
            $code = 400;
        }
        return $this->json($account, $code, [
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json"
        ], []);
    }
}