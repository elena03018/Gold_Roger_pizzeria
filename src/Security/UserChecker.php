<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

    }    

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) 
        {
            
            return;
        }

        // user account is expired, the user may be notified
        if (! $user->isVerified() ) 
        {
            throw new CustomUserMessageAccountStatusException('Veuillez confirmer votre compte par email avant de vous connecter.');
        }
    }
}