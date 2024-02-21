<?php

namespace App\Command;

use App\Movie\Enum\SearchType;
use App\Movie\Provider\MovieProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:movie:import',
    description: 'Import a movie from OMDB API',
)]
class ImportMovieCommand extends Command
{
    public function __construct(private readonly MovieProvider $movieProvider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('omdbId', null,InputOption::VALUE_OPTIONAL, 'OMDB Id')
            ->addOption('title', null,InputOption::VALUE_OPTIONAL, 'movie title')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('importing movie from omdb');

        $movie = null;
        $omdbId = $input->getOption('omdbId');
        $title = $input->getOption('title');
        if ($omdbId) {
            $io->section('finding movie by omdb id');
            $movie = $this->movieProvider->getMovie(SearchType::OmdbId, $omdbId);
        }
        if ($title) {
            $io->section('finding movie by title');
            $movie = $this->movieProvider->getMovie(SearchType::Title, $title);
        }

        if (!$movie) {
            $io->warning('Movie not found');

            return Command::FAILURE;
        }

        $io->table([
            'Title',
            'id from omdb',
            'id from our db',
            'MPAA'
        ], [
            $movie->getTitle(),
            $movie->getImdbId(),
            $movie->getId(),
            $movie->getRated(),
        ]);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
