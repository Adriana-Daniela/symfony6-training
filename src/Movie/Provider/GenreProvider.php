<?php

namespace App\Movie\Provider;

use App\Entity\Genre;
use App\Movie\Transformer\OmdbToGenreTransformer;
use App\Repository\GenreRepository;

class GenreProvider
{
    public function __construct(
        protected readonly GenreRepository $repository,
        protected readonly OmdbToGenreTransformer $transformer,
    )
    {
    }

    public function getGenre(string $value): Genre
    {
        return $this->repository->findOneBy(['name' => $value])
            ?? $this->transformer->transform($value);
    }

    public function getFromOmdb(array $value): iterable
    {
        if (!\array_key_exists('Genre', $value)) {
            throw new \InvalidArgumentException();
        }

        foreach (explode(', ', $value['Genre']) as $name) {
            yield $this->getGenre($name);
        }
    }
}
