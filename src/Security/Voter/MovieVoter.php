<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use App\Movie\Event\MovieUnderageEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MovieVoter extends Voter
{
    public const RATED = 'movie.rated';
    public const EDIT = 'movie.edit';

    public function __construct(protected readonly EventDispatcherInterface $dispatcher)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Movie
            && \in_array($attribute, [self::RATED, self::EDIT]);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::RATED => $this->checkRated($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };
    }

    private function checkRated(Movie $movie, User $user): bool
    {
        $vote = match ($movie->getRated()) {
            'G' => true,
            'PG', 'PG-13' => $user->getAge() && $user->getAge() >= 13,
            'R', 'NC-17' => $user->getAge() && $user->getAge() >= 17,
            default => false,
        };

        if (false === $vote) {
            $this->dispatcher->dispatch(new MovieUnderageEvent($movie, $user));
        }

        return $vote;
    }

    private function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkRated($movie, $user) && $movie->getCreatedBy() === $user;
    }
}
