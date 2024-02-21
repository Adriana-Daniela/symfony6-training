<?php

namespace App\Command;

use App\Movie\Enum\SearchType;
use App\Movie\Provider\MovieProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsCommand(
    name: 'app:movie:find',
    description: 'Search a movie by its title or IMDb ID',
)]
class MovieFindCommand extends Command
{
    private ?SymfonyStyle $io = null;
    public function __construct(private readonly MovieProvider $provider)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('type', InputArgument::OPTIONAL, 'The type of search to perform (Id or Title)')
            ->addArgument('value', InputArgument::OPTIONAL, 'The value you are searching for')
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->provider->setIo($this->io);

        if (!$input->getArgument('type')) {
            $input->setArgument('type', $this->io->choice('What type of search do you want to perform ?', ['Id', 'Title']));
        }

        $input->setArgument('type', SearchType::getFromLabel($input->getArgument('type')));

        if (!$input->getArgument('value')) {
            $input->setArgument('value', $this->io->ask('What movie are you searching for ?'));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$input->getArgument('type') || !$input->getArgument('value')) {
            throw new \InvalidArgumentException();
        }

        /** @var SearchType $type */
        $type = $input->getArgument('type');
        $value = $input->getArgument('value');

        $this->io->title(sprintf("Searching for a movie with a %s '%s'.", $type->getLabel(), $value));

        try {
            $movie = $this->provider->getMovie($type, $value);
        } catch (NotFoundHttpException) {
            $this->io->error('Movie not found!');

            return Command::FAILURE;
        } catch (\Exception $e) {
            $this->io->error('Something went wrong: '.$e->getMessage());

            return Command::FAILURE;
        }

        $this->io->table(
            ['Id', 'IMDb Id', 'Title', 'Rated'],
            [[$movie->getId(), $movie->getImdbId(), $movie->getTitle(), $movie->getRated()]]
        );
        $this->io->success('Movie found!');

        return Command::SUCCESS;
    }
}
