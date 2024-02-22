<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

final class LastConnectedListener
{
    public function __construct(protected readonly EntityManagerInterface $manager)
    {
    }

    #[AsEventListener(event: 'security.interactive_login')]
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): void
    {
        if (($user = $event->getAuthenticationToken()?->getUser()) instanceof User) {
            /** @var User $user */
            $user->setLastConnectedAt(new \DateTimeImmutable());
            $this->manager->persist($user);
            $this->manager->flush();
        }
    }
}
