<?php

namespace App\Movie\Provider;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Consumer\MovieConsumerInterface;
use App\Movie\Enum\SearchType;
use App\Movie\Transformer\OmdbToMovieTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieProvider
{
    protected ?SymfonyStyle $io = null;

    public function __construct(
        protected readonly EntityManagerInterface $manager,
        protected readonly MovieConsumerInterface $consumer,
        protected readonly OmdbToMovieTransformer $transformer,
        protected readonly GenreProvider $genreProvider,
        protected readonly Security $security,
    )
    {
    }

    public function getMovie(SearchType $type, string $value): Movie
    {
        if ($movie = $this->manager->getRepository(Movie::class)->getOmdbSearch($type, $value)) {
            $this->io?->info('Movie already in database!');

            return $movie;
        }

        $this->io?->text('Searching movie on OMDbAPI...');
        $data = $this->consumer->fetchMovieData($type, $value);
        $this->io?->info('Movie found! Saving in database.');
        $movie = $this->transformer->transform($data);

        foreach ($this->genreProvider->getFromOmdb($data) as $genre) {
            $movie->addGenre($genre);
        }

        if (($user = $this->security->getUser()) instanceof User) {
            $movie->setCreatedBy($user);
        }

        $this->manager->persist($movie);
        $this->manager->flush();
        $this->io?->text('Movie saved in database.');

        return $movie;
    }

    public function setIo(?SymfonyStyle $io): void
    {
        $this->io = $io;
    }
}
