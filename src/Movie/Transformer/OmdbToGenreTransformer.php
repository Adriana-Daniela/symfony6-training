<?php

namespace App\Movie\Transformer;

use App\Entity\Genre;

class OmdbToGenreTransformer implements OmdbTransformerInterface
{
    public function transform(array|string $value): Genre
    {
        if (!\is_string($value) || str_contains($value, ',')) {
            throw new \InvalidArgumentException();
        }

        return (new Genre())->setName($value);
    }
}

