<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    public function onKernelRequestPre(RequestEvent $event): void
    {
        // ...
    }

    public function onKernelRequestPost(RequestEvent $event): void
    {
        // ...
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [
                ['onKernelRequestPre', 1000],
                ['onKernelRequestPost', -1000],
            ],
        ];
    }
}
