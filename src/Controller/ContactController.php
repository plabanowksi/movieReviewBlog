<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactFormType;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class ContactController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em) 
    {
        $this->em = $em;
    }

    #[Route('/contact', methods:['GET','POST'], name: 'contact')]
    public function index(Request $request): Response
    {
        $contactForm = $this->createForm(ContactFormType::class);
        $contact = new Contact();
        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() && $contactForm->isValid()){
            $contact->setEmail($contactForm->get('email')->getData());
            $contact->setContent($contactForm->get('content')->getData());
            $contact->setCreatedAtValue();

            $userEmail = $contactForm->get('email')->getData();

            $this->em->persist($contact);
            $this->em->flush();

            $email = (new Email())
                ->to($userEmail)
                ->from('noreply@movie.com')
                ->subject('Contact Us - Movie team reply')
                ->text('Contact Us
                Thank you for contacting us!
                We will respond and try to help as fast as possible
                This message was generated automatically by MovieTeam.')
                ->html('<h1>Contact Us</h1>
                <p>Thank you for contacting us!</p>
                <p>We will respond and try to help as fast as possible.</p>
                <p>This message was generated automatically by MovieTeam.</p>');

            $dsn = 'gmail+smtp://randomsendemailmailer@gmail.com:jainrpqwcpxefeqd@default';

            $transport = Transport::fromDsn($dsn);
            $mailer = new Mailer($transport);
            $mailer->send($email);

            $this->addFlash('success','Email send - sucess');
        }

        return $this->render('menu/contact.html.twig',[
            'contactForm' => $contactForm
        ]);
    }
}
