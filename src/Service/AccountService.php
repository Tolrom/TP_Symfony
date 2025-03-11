<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account;
use App\Repository\AccountRepository;

class AccountService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AccountRepository $accountRepository
    ) {}


    public function save(Account $account)
    {
        // Making sure inputs are filled
        if (
            $account->getFirstname() != "" && $account->getLastname() != "" && $account->getEmail() != "" &&
            $account->getPassword() != ""
        ) {
            // Check if account exists
            if(!$this->accountRepository->findOneBy(["email"=>$account->getEmail()])) {
                // Setting parameters
                $account->setRole("ROLE_USER");
                $this->em->persist($account);
                $this->em->flush();
            }
            else {
                throw new \Exception("Account already exists");
            }
        }
        
        else {
            throw new \Exception("Every input isn't filled");
        }
    }

    public function getAll() {
        $users = $this->em->getRepository(Account::class)->findAll();
        if ($users != []) {
            return $users;
        }
        else {
            throw new \Exception("No account fetched in the database");
        }
    }

    public function getById(int $id) {
        $user = $this->em->getRepository(Account::class)->find($id);
        if($user) {
            return $user;
        }
        else {
            throw new \Exception("No user found");
        }
    }
}