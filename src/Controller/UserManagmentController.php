<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserManagmentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserManagmentController extends AbstractController
{
    private $em;
    private $umService;
    public function __construct(UserManagmentService $umService, EntityManagerInterface $em) 
    {
        $this->em = $em;
        $this->umService = $umService;
    }

    #[Route('/admin/users/view', methods:['GET'], name: 'admin_user_view')]
    public function view(): Response
    {
        return $this->render('adminpanel/user_managment/usersview.html.twig', [
            'users' => $this->em->getRepository(User::class)->findAll(),
            'userRoles' => $this->umService->loadUserRoles(),

        ]);
    }

    #[Route('/admin/users/switchActive/{id}', methods:['GET','POST'], name: 'admin_disable&delete')]
    public function disableOrDelete(int $id): Response
    {
        if($this->umService->changeUserRole($id))
            $this->addFlash('success','User updated success');

        return $this->redirectToRoute('admin_user_view');
    }

    #[Route('/admin/users/roles&permissions', methods:['GET','POST'], name: 'admin_roles&permissions')]
    public function manageRoles(int $id): Response
    {
        // $this->addFlash('success','User updated success');
        return $this->render('adminpanel/user_managment/usersview.html.twig', [
        ]);
    }

    #[Route('/admin/users/delete/{id}', methods: ['GET','DELETE'], name: 'delete_admin')]
    public function delete(int $id): Response
    {
        if($this->umService->deleteUser($id))
            $this->addFlash('success','User delete - success');

        return $this->redirectToRoute('admin_user_view');
    }

}
