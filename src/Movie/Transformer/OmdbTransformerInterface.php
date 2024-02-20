<?php

namespace App\Movie\Transformer;

interface OmdbTransformerInterface
{
    public function transform(string|array $value): object;
}
