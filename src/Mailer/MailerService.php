<?php


namespace App\Mailer;


use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;
    const FROM = "lairofclaw@gmail.com";

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function respondContact(Contact $contact){
        $email = (new Email())
            ->from(self::FROM)
            ->to($contact->getEmail())
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);
    }
}