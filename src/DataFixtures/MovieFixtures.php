<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    private function getMovies(): iterable
    {
        foreach ($this->getMoviesData() as $moviesDatum) {
            $movie = (new Movie())
                ->setTitle($moviesDatum['title'])
                ->setPlot($moviesDatum['plot'])
                ->setCountry($moviesDatum['country'])
                ->setPrice($moviesDatum['price'])
                ->setPoster($moviesDatum['poster'])
                ->setReleasedAt($moviesDatum['releasedAt']);

            
        }
    }

    private function getMoviesData(): iterable
    {
        $files = (new Finder())
            ->in(__DIR__)
            ->files()
            ->name('movies_fixtures.json');

        foreach ($files as $file) {
            $content = $file->getContents();

            foreach (\json_decode($content, true) as $data) {
                yield $data;
            }
        }
    }
}
