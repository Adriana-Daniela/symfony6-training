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

    public function getLabel(): string
    {
        return match ($this) {
            self::Id => 'Id',
            self::Title => 'Title',
        };
    }

    public static function getFromLabel(string $label): self
    {
        return match (ucfirst($label)) {
            'Id' => self::Id,
            'Title' => self::Title,
            default => throw new \InvalidArgumentException('Non existent case'),
        };
    }
}
