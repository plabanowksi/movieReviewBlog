<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\UserRole;

class UserManagmentService
{

    private $userRepository;
    private $em;
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository) 
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function loadUserRoles()
    {
        return [
            'admin' => UserRole::ADMIN,
            'moderator' => UserRole::MODERATOR,
            'user' => UserRole::USER,
        ];
    }

    public function changeUserRole(int $id): void
    {
        $user = $this->em->getRepository(User::class)->find($id);;

        if($user->isIsBlocked() == true)
            $user->setIsBlocked(false);
        else
            $user->setIsBlocked(true);

        $this->em->flush();
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        $this->em->remove($user);
        $this->em->flush();
    }
}
