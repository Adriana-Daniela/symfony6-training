<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class BookFixtures extends Fixture
{
    use FixtureFileImporterTrait;

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getBooks() as $book) {
            $manager->persist($book);
        }

        $manager->flush();
    }

    private function getBooks(): iterable
    {
        foreach ($this->getData('book_fixtures.json') as $datum) {
            $book = (new Book())
                ->setTitle($datum['title'])
                ->setCover($datum['cover'])
                ->setAuthor($datum['author'])
                ->setIsbn($datum['isbn'])
                ->setPlot($datum['plot'])
                ->setReleasedAt(new \DateTimeImmutable($datum['releasedAt']));

            yield $book;
        }
    }
}
