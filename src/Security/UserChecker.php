<?php

namespace App\Security;



use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

use Symfony\Component\Security\Core\User\UserCheckerInterface;

use Symfony\Component\Security\Core\User\UserInterface;



class UserChecker implements UserCheckerInterface

{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


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

        if (!$user instanceof User) {
            return;
        }

        // Réactiver si inactif
        if ($user->isActive() === false) {
            $user->setIsActive(true);
            $this->em->flush();
        }
    }
}
