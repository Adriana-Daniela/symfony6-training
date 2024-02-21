<?php

namespace App\Movie\Transformer;

use App\Entity\Movie;

class OmdbToMovieTransformer implements OmdbTransformerInterface
{
    public const KEYS = [
        'Title',
        'Released',
        'Year',
        'Plot',
        'Country',
        'Poster',
        'Rated',
        'imdbID',
    ];

    public function transform(array|string $value): Movie
    {
        if (!\is_array($value) || 0 < \count(\array_diff(self::KEYS, \array_keys($value)))) {
            throw new \InvalidArgumentException();
        }

        $date = $value['Released'] === 'N/A' ? '01-01-'.$value['Year'] : $value['Released'];

        return (new Movie())
            ->setTitle($value['Title'])
            ->setPlot($value['Plot'])
            ->setCountry($value['Country'])
            ->setReleasedAt(new \DateTimeImmutable($date))
            ->setPoster($value['Poster'])
            ->setRated($value['Rated'])
            ->setImdbId($value['imdbID'])
            ->setPrice(5.0)
            ;
    }
}
