<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Movie\Consumer\MovieConsumerInterface;
use App\Movie\Enum\SearchType;
use App\Movie\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;

class MovieProvider
{

    public function __construct(
        protected readonly EntityManagerInterface $manager,
        protected readonly MovieConsumerInterface $consumer,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
    )
    {
    }

    public function getMovie(SearchType $type, string $value): Movie
    {
        if ($movie = $this->manager->getRepository(Movie::class)->getOmdbSearch($type, $value)) {
            return $movie;
        }

        $data = $this->consumer->fetchMovieData($type, $value);
        $movie = $this->transformer->transform($data);

        foreach ($this->genreProvider->getFromOmdb($data) as $genre) {
            $movie->addGenre($genre);
        }

        $this->manager->persist($movie);
        $this->manager->flush();

        return $movie;
    }
}
