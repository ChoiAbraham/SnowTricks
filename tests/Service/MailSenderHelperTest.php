<?php

namespace App\Tests\Service;

use App\Domain\Entity\User;
use App\Service\MailSenderHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

class MailSenderHelperTest extends TestCase
{
    public function testSendTemplatedEmailForPasswordReset()
    {
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects($this->once())
            ->method('send');

        $user = new User();
        $user->setName('Matthieu');
        $user->setEmail('matthieu@symfony82.com');

        $token = md5(uniqid(rand()));
        $user->setToken($token);

        $mailer = new MailSenderHelper($symfonyMailer);
        $email = $mailer->sendTemplatedEmailForPasswordReset($token, $user->getEmail(), $user->getName());

        $this->assertSame('Changement du mot de passe', $email->getSubject());
        $this->assertCount(1, $email->getTo());
        $namedAddresses = $email->getTo();
        $this->assertInstanceOf(Address::class, $namedAddresses[0]);
        $this->assertSame('Matthieu', $namedAddresses[0]->getName());
        $this->assertSame('matthieu@symfony82.com', $namedAddresses[0]->getAddress());
    }
}
