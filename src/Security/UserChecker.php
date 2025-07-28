<?php

namespace App\Security;



use App\Entity\User;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

use Symfony\Component\Security\Core\User\UserCheckerInterface;

use Symfony\Component\Security\Core\User\UserInterface;



class UserChecker implements UserCheckerInterface

{

    public function checkPreAuth(UserInterface $user): void

    {

        if (!$user instanceof User) {

            return;
        }

        if ($user->isBanned()) {

            throw new CustomUserMessageAuthenticationException(

                'Le compte est désactivé! Contactez l\'administrateur pour plus d\'informations.'

            );
        }
    }

    public function checkPostAuth(UserInterface $user): void

    {

        $this->checkPreAuth($user);
    }
}
