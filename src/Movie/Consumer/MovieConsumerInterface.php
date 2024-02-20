<?php

namespace App\Movie\Consumer;

use App\Movie\Enum\SearchType;

interface MovieConsumerInterface
{
    public function fetchMovieData(SearchType $type, string $value): array;
}
