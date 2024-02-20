<?php

namespace App\DataFixtures;

use Symfony\Component\Finder\Finder;

trait FixtureFileImporterTrait
{
    public function getData(string $filename): iterable
    {
        $files = (new Finder())
            ->in(__DIR__)
            ->files()
            ->name($filename);

        foreach ($files as $file) {
            $content = $file->getContents();

            foreach (\json_decode($content, true) as $item) {
                yield $item;
            }
        }
    }
}
