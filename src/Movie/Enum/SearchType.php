<?php

namespace App\Movie\Enum;

enum SearchType
{
    case Id;
    case Title;

    public function getSearchParam(): string
    {
        return match ($this) {
            self::Id => 'i',
            self::Title => 't',
        };
    }
}
