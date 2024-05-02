<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Service\ContactService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', methods:['GET','POST'], name: 'contact')]
    public function index(Request $request, ContactService $contactService): Response
    {
        $contactForm = $this->createForm(ContactFormType::class);
        
        $contactForm->handleRequest($request);
        if($contactService->processContactForm($contactForm))
            $this->addFlash('success','Email sended successfully');
        else
            $this->addFlash('warrning','Can not send email');

        return $this->render('menu/contact.html.twig', [
            'contactForm' => $this->createForm(ContactFormType::class)->createView()
        ]);
    }
}