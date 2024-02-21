<?php

namespace App\Security\Voter;

use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

#[AutoconfigureTag('security.voter', attributes: ['priority' => 500])]
class AdminVoter implements VoterInterface
{
    public function vote(TokenInterface $token, mixed $subject, array $attributes)
    {
        $user = $token->getUser();
        if ($user && \in_array('ROLE_ADMIN', $token->getRoleNames())) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
