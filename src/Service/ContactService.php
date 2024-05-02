<?php

namespace App\Service;

use App\Entity\Contact;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class ContactService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function processContactForm(FormInterface $contactForm): void
    {
        if (!($contactForm->isSubmitted() && $contactForm->isValid())) { return ; }
        
        $contact = new Contact();
        $contact->setEmail($contactForm->get('email')->getData());
        $contact->setContent($contactForm->get('content')->getData());
        $contact->setCreatedAtValue();

        $this->em->persist($contact);
        $this->em->flush();

        $userEmail = $contact->getEmail();
        try {
            $this->sendEmail($userEmail);
        } catch (TransportExceptionInterface $e) {
        }
    }

    private function sendEmail(string $userEmail): void
    {
        $email = (new Email())
            ->to($userEmail)
            ->from('noreply@movie.com')
            ->subject('Contact Us - Movie team reply')
            ->text('Thank you for contacting us! We will respond as soon as possible.')
            ->html('<h1>Contact Us</h1>
                    <p>Thank you for contacting us!</p>
                    <p>We will respond as soon as possible.</p>');

            //uncomment $dsn and set email and password for mailer
            // $dsn = 'gmail+smtp://email@gmail.com:password@default'; 
            $dsn = false;
            if($dsn == false){
                return ;
            }

        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);
        $mailer->send($email);

    }
}
