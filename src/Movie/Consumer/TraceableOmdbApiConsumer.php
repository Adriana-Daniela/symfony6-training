<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(MovieConsumerInterface::class, priority: 10)]
class TraceableOmdbApiConsumer implements MovieConsumerInterface
{
    public function __construct(
        protected readonly MovieConsumerInterface $inner,
        protected readonly LoggerInterface $logger,
    )
    {
    }

    public function fetchMovieData(SearchType $type, string $value): array
    {
        $this->logger->info(sprintf("New movie searched: %s = %s", $type->getSearchParam(), $value));

        return $this->inner->fetchMovieData($type, $value);
    }

    /**
     * @return MovieConsumerInterface
     */
    public function getInner(): MovieConsumerInterface
    {
        return $this->inner;
    }
}
