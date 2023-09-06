<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Publisher;
use App\Entity\Status;
use App\Entity\User;
use App\Entity\UserBook;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker::create('fr_FR');

        // Création de 10 auteurs
        $authors = [];

        for ($i = 0; $i < 10; ++$i) {
            $author = new Author();
            $author->setName($faker->name);
            $manager->persist($author);
            $authors[] = $author;
        }

        // Création de 10 éditeurs
        $publishers = [];

        for ($i = 0; $i < 10; ++$i) {
            $publisher = new Publisher();
            $publisher->setName($faker->company);
            $manager->persist($publisher);
            $publishers[] = $publisher;
        }

        // Créations des Status
        $status = [];

        foreach (['A lire', 'En cours', 'Lu'] as $value) {
            $oneStatus = new Status();
            $oneStatus->setName($value);
            $manager->persist($oneStatus);
            $status[] = $oneStatus;
        }

        // Creation de 100 livres
        $books = [];

        for ($i = 0; $i < 100; ++$i) {
            $book = new Book();

            /** @phpstan-ignore-next-line */
            $isbn10 = $faker->isbn10;

            /** @phpstan-ignore-next-line */
            $isbn13 = $faker->isbn13;

            $book
                ->setGoogleBooksId($faker->uuid())
                ->setTitle($faker->sentence)
                ->setSubtitle($faker->sentence)
                ->setPublishDate($faker->dateTime)
                ->setDescription($faker->text)
                ->setIsbn10($isbn10)
                ->setIsbn13($isbn13)
                ->setPageCount($faker->numberBetween(100, 1000))
                ->setThumbnail($faker->imageUrl(200, 300))
                ->setSmallThumbnail($faker->imageUrl(100, 150))
                ->addAuthor($faker->randomElement($authors))
                ->addPublisher($faker->randomElement($publishers))
            ;
            $manager->persist($book);

            $books[] = $book;
        }

        // Creation de 10 utilisateurs
        $users = [];

        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user
                ->setEmail($faker->email)
                ->setPassword($faker->password)
                ->setPseudo($faker->userName)
            ;
            $manager->persist($user);

            $users[] = $user;
        }

        // Creation 10 userBook par User
        foreach ($users as $user) {
            for ($i = 0; $i < 10; ++$i) {
                $userBook = new UserBook();
                $userBook
                    ->setReader($user)
                    ->setStatus($faker->randomElement($status))
                    ->setRating($faker->numberBetween(0, 5))
                    ->setComment($faker->text)
                    ->setBooks($faker->randomElement($books))
                    ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime))
                    ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime))
                ;
                $manager->persist($userBook);
            }
        }

        $manager->flush();
    }
}
