<?php

namespace App\Security\Voter;

use App\Entity\Book;
use App\Entity\User;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

#[AutoconfigureTag('security.voter', attributes: ['priority' => 300])]
class BookEditVoter extends Voter
{
    public const EDIT = 'book.edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return self::EDIT === $attribute && $subject instanceof Book;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return \in_array('ROLE_ADMIN', $token->getRoleNames());
        }

        /** @var Book $subject */
        return $subject->getCreatedBy() === $user;
    }
}
