<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Enum\UserRole;

class UserManagmentController extends AbstractController
{

    private $userRepository;
    private $em;
    public function __construct(EntityManagerInterface $em, UserRepository $userRepository) 
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    #[Route('/admin/users/view', name: 'admin_user_view')]
    public function view(): Response
    {
        $userRoles = [
            'admin' => UserRole::ADMIN,
            'moderator' => UserRole::MODERATOR,
            'user' => UserRole::USER,
        ];

        $users = $this->userRepository->findAll();
        return $this->render('adminpanel/user_managment/usersview.html.twig', [
            'users' => $users,
            'userRoles' => $userRoles,

        ]);
    }
    #[Route('/admin/users/switchActive/{id}', name: 'admin_disable&delete')]
    public function disableOrDelete(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if($user->isIsBlocked() == true)
            $user->setIsBlocked(false);
        else
            $user->setIsBlocked(true);

        $this->em->flush();

        $this->addFlash('success','User updated success');

        return $this->redirectToRoute('admin_user_view');
    }

    #[Route('/admin/users/roles&permissions', name: 'admin_roles&permissions')]
    public function manageRoles(int $id): Response
    {
        // $this->addFlash('success','User updated success');
        return $this->render('adminpanel/user_managment/usersview.html.twig', [
        ]);
    }

    #[Route('/admin/users/delete/{id}', methods: ['GET','DELETE'], name: 'delete_admin')]
    public function delete(int $id): Response
    {
        $user = $this->userRepository->find($id);
        $this->em->remove($user);
        $this->em->flush();

        $this->addFlash('success','User delete - success');

        return $this->redirectToRoute('admin_user_view');
    }

}
