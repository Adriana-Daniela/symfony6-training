<?php

namespace App\EventListener;

use App\Movie\Event\MovieSearchEvent;
use App\Notifications\AppNotifier;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

final class MovieSearchListener
{
    public function __construct(protected readonly AppNotifier $notifier)
    {
    }

    #[AsEventListener(event: MovieSearchEvent::class)]
    public function onMovieSearchEvent(MovieSearchEvent $event): void
    {
        $this->notifier->sendNewEntryNotification($event->getMovie(), $event->getUser());
    }
}
