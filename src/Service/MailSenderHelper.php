<?php

namespace App\Service;

use App\Responders\ViewResponder;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class MailSenderHelper
{
    /** @var MailerInterface */
    private $mailer;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Environment $twig, MailerInterface $mailer)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendTemplatedEmailForPasswordReset($token, string $emailUser, string $firstName): TemplatedEmail
    {
        $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to(new Address($emailUser, $firstName), )
            ->subject('Changement du mot de passe')
            ->htmlTemplate('emails/_password_recovery.html.twig')
            ->context([
                'tokenSent' => $token
            ]);

        $this->mailer->send($email);

        return $email;
    }

    public function getTwigTemplate(ViewResponder $responder)
    {
        return $responder('emails/_password_recovery.html.twig', []);
    }
}
