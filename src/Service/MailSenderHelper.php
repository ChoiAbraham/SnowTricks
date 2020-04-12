<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\Address;

class MailSenderHelper
{
    /** @var MailerInterface */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailWithTemplateTwig($token, string $emailUser, string $firstName)
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to(new Address($emailUser, $firstName), )
            ->subject('Changement du mot de passe')
            ->htmlTemplate('emails/_password_recovery.html.twig')
            ->context([
                'token' => $token
            ]);

        /**
         * @var SentMessage $sentEmail
         */
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            dump($e);
        }
    }
}
