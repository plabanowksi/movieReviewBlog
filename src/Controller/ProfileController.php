<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\ProfileFormType;
use App\Repository\CommentsRepository;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    private $em;
    private $userRepository;
    private $movieRepository;
    private $commentsRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, MovieRepository $movieRepository, CommentsRepository $commentsRepository) 
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->movieRepository = $movieRepository;
        $this->commentsRepository = $commentsRepository;
    }

    #[Route('/profile', methods:['GET'], name: 'profile')]
    public function index(): Response
    {
        return $this->render('profile/profile.html.twig', [
        ]);
    }

    #[Route('/profile/edit', methods:['GET','POST'], name: 'edit_profile')]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $userInfo = $this->userRepository->find($user);
        $profileForm = $this->createForm(ProfileFormType::class, $user);
        $profileForm->handleRequest($request);

        if($profileForm->isSubmitted() && $profileForm->isValid()){
                $userInfo->setEmail($profileForm->get('email')->getData());
                $userInfo->setPassword(
                    $userPasswordHasher->hashPassword(
                    $userInfo,
                    $profileForm->get('password')->getData()
                    )
                );
                $userInfo->setName($profileForm->get('name')->getData());
                $userInfo->setSurename($profileForm->get('surename')->getData());

                $this->em->flush();
                $this->addFlash('success','Profile edited sucessfully');
                return $this->redirectToRoute('edit_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'userInfo' => $userInfo,
            'profileForm' => $profileForm,

        ]);
    }

    #[Route('/profile/myreviews', methods:['GET'], name: 'myreviews_profile')]
    public function myreviews(): Response
    {
        $user = $this->getUser();
        $reviews = $this->em->getRepository(Comments::class)->findBy(['user' => $user]);


        return $this->render('profile/myreviews.html.twig', [
            'user' => $user,
            'reviews' => $reviews
        ]);
    }

    #[Route('/adminpanel', methods:['GET'], name: 'adminpanel_adminpanel')]
    public function adminpanel(): Response
    {

        return $this->render('adminpanel/adminpanel.html.twig', [
        ]);
    }


}
