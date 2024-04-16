<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(): Response
    {
        return $this->render('menu/contact.html.twig');
    }

    #[Route('/contact/email', name: 'send_email')]
    public function sendEmail(Request $request): Response
    {
        dd($request);
        return $this->render('menu/contact.html.twig');
    }

}
