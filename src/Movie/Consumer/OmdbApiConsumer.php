<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsAlias]
class OmdbApiConsumer implements MovieConsumerInterface
{
    public function __construct(protected HttpClientInterface $omdbClient)
    {
    }

    public function fetchMovieData(SearchType $type, string $value): array
    {
        $data = $this->omdbClient->request(
            'GET',
            '',
            [
                'query' => [
                    $type->getSearchParam() => $value,
                ]
            ]
        )->toArray();

        if (\array_key_exists('Error', $data)) {
            if ('Movie not found!' === $data['Error']) {
                throw new NotFoundHttpException('Movie not found!');
            }

            throw new \RuntimeException($data['Error']);
        }

        return $data;
    }
}
