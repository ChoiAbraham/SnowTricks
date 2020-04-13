<?php

namespace App\Tests\Controller;

use App\DataFixtures\UserFixture;
use App\Tests\AbstractWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\Mime\Header\MailboxListHeader;

class SecurityRequestNewPasswordActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

    public function testSuccessfulRequestForNewPassword()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        $link = $crawler->selectLink('Mot de passe oublié?')->link();
        $crawler = $this->client->click($link);

        static::assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer');
        $form = $button->form();
        $this->client->submit($form, [
            'email_for_password_recovery_form[pseudo]' => 'Abraham Choi',
            'email_for_password_recovery_form[email]' => 'abraham.choi@yahoo.fr',
        ]);

        static::assertTrue($this->client->getResponse()->isRedirection());

        static::assertEmailCount(1);

        $email = static::getMailerMessage(0);

//        dd($email);

        // run : php bin/phpunit tests/Actions/SecurityRequestNewPasswordActionFunctionalTest.php --filter=testSuccessfulRequestForNewPassword
        // question : how to get the address 'abraham.choi@yahoo.fr, it says an object MailboxListHeader.
//        static::assertEmailHeaderSame($email, 'To', 'abraham.choi@yahoo.fr');
        static::assertEmailTextBodyContains($email, 'SnowTricks');
    }

    public function testFormWithWrongCredentials()
    {
        $this->loadFixtures([UserFixture::class]);


        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        $link = $crawler->selectLink('Mot de passe oublié?')->link();
        $crawler = $this->client->click($link);
        static::assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer');
        $form = $button->form();
        $form['email_for_password_recovery_form[pseudo]']->setValue('Abraham');
        $form['email_for_password_recovery_form[email]']->setValue('abraham@yahoo.fr');
        $this->client->submit($form);

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();

        static::assertSelectorTextContains('.flash-homepage', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');
    }

    public function testFormWithNoCredentials()
    {
        $this->loadFixtures([UserFixture::class]);


        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        $link = $crawler->selectLink('Mot de passe oublié?')->link();
        $crawler = $this->client->click($link);

        static::assertResponseIsSuccessful();

        $button = $crawler->selectButton('Envoyer');
        $form = $button->form();
        $form['email_for_password_recovery_form[pseudo]']->setValue('');
        $form['email_for_password_recovery_form[email]']->setValue('');
        $crawler = $this->client->submit($form);

        static::assertSame(2, $crawler->filter('html span.form-error-icon')->count());
    }
}