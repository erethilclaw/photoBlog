<?php

namespace App\Mailer;

use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    private $mailer;
    const FROM = "lairofclaw@gmail.com";

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function respondContact(Contact $contact){
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to($contact->getEmail())
            ->subject('Lair of claw mail!!')
            ->text($contact->getMessage())
           // ->html('<p>See Twig integration for better HTML integration!</p>');
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'date' => new \DateTime(),
                'contact' => $contact
            ]);
        $this->mailer->send($email);
    }
}