<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    use FixtureFileImporterTrait;

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getMovies() as $movie) {
            $manager->persist($movie);
        }

        $manager->flush();
    }

    public function getMovies(): iterable
    {
        $genres = [];
        foreach ($this->getData('movie_fixtures.json') as $datum) {
            $date = $datum['Released'] === 'N/A' ? $datum['Year'] : $datum['Released'];

            $movie = (new Movie())
                ->setTitle($datum['Title'])
                ->setPlot($datum['Plot'])
                ->setCountry($datum['Country'])
                ->setReleasedAt(new \DateTimeImmutable($date))
                ->setPoster($datum['Poster'])
                ->setPrice(5.0)
                ->setRated($datum['Rated'])
                ->setImdbId($datum['imdbID'])
            ;

            foreach (explode(', ', $datum['Genre']) as $genreName) {
                $genre = $genres[$genreName]
                    ?? $genres[$genreName] = (new Genre())->setName($genreName);
                $movie->addGenre($genre);
            }

            yield $movie;
        }
    }
}
