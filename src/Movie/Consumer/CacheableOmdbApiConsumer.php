<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\When;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[When(env: 'prod')]
#[AsDecorator(MovieConsumerInterface::class, priority: 5)]
class CacheableOmdbApiConsumer implements MovieConsumerInterface
{
    public function __construct(
        protected MovieConsumerInterface $inner,
        protected CacheInterface $cache,
        protected SluggerInterface $slugger,
    )
    {
    }

    public function fetchMovieData(SearchType $type, string $value): array
    {
        $key = $this->slugger->slug(sprintf("%s_%s", $type->getSearchParam(), $value));

        return $this->cache->get(
            $key,
            function (ItemInterface $item) use ($type, $value) {
                $item->expiresAfter(3600);

                return $this->inner->fetchMovieData($type, $value);
            }
        );
    }

    /**
     * @return MovieConsumerInterface
     */
    public function getInner(): MovieConsumerInterface
    {
        return $this->inner;
    }
}
