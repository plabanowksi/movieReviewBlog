<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\RegistrationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegistrationController extends AbstractController
{
    #[Route('/register' ,methods:['GET','POST'], name: 'app_register')]
    public function register(Request $request, RegistrationService $rs): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if($rs->register($form, $user)){
            $this->addFlash('success','Account created successfully, You can log in now');
            return $this->redirectToRoute('movies');
        }

        
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $$rs->getRegistrationForm(),
        ]);
    }
}
