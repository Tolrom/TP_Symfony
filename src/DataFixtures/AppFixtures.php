<?php

namespace App\DataFixtures;

use App\Entity\Account;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $accounts = [];
        $categories = [];
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50 ; $i++) { 
            $account = new Account();
            $account->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setEmail($faker->email())
                    ->setPassword($faker->password())
                    ->setRole('ROLE_USER');
            $manager->persist($account);
            $accounts[] = $account;
        }
        for ( $i = 0 ; $i < 30 ; $i++) { 
            $category = new Category();
            $category->setName($faker->unique()->jobTitle());
            $manager->persist($category);
            $categories[] = $category;
        }

        for ($i=0; $i < 100 ; $i++) { 
            $article = new Article();
            $article->setTitle($faker->sentence())
                    ->setContent($faker->paragraph())
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeThisMonth()))
                    ->setAuthor($faker->randomElement($accounts));
            $selectedCategories = [];
            while (count($selectedCategories) < 3) {
                $category = $faker->randomElement($categories);
                if (!in_array($category, $selectedCategories, true)) {
                    $selectedCategories[] = $category;
                    $article->addCategory($category);
                }
            }
            $manager->persist($article);
        }

        $manager->flush();
    }
}
