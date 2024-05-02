<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationService
{
    private $userPasswordHasher;
    private $em;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em) 
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->em = $em;
    }

    #[Route('/register', name: 'app_register')]
    public function register(FormInterface $form, User $user): void
    {
        if (!($form->isSubmitted() && $form->isValid())) {return ; }
        $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $user->setRoles($user->getRoles());
        $this->em->persist($user);
        $this->em->flush();
        
    }
}
