<?php

namespace App\Tests\Actions\Security;

use App\DataFixtures\UserFixture;
use App\Tests\AbstractWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class SecurityRegistrationActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

    public function testSuccessAddNewUser()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');
        // static::assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        // same as
        static::assertTrue($this->client->getResponse()->isSuccessful());

        $link = $crawler->selectLink('Inscription')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('S\'inscrire')->form();

        $this->client->submit($form, [
            'registration[username]' => 'VeryUpsetting',
            'registration[email]' => 'veryupset@gmail.com',
            'registration[password]' => 'weirdweird6',
            'registration[confirm_password]' => 'weirdweird6',
        ]);

        static::assertTrue($this->client->getResponse()->isRedirection());

        $crawler = $this->client->followRedirect();

        static::assertSelectorNotExists('.invalid-feedback');
        static::assertSame(1, $crawler->filter('html:contains("vous êtes inscrit")')->count());
    }

    public function testRegistrationWithBadCredentials()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Inscription')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton("S'inscrire")->form();
        //Same Username
        $form['registration[username]'] = 'Abraham Choi';
        //Same Email
        $form['registration[email]'] = 'abraham.choi@yahoo.fr';
        $form['registration[password]'] = 'abrahamchoi';
        $form['registration[confirm_password]'] = 'abrahamchoi';

        $crawler = $this->client->submit($form);
        static::assertSelectorExists('span.invalid-feedback');

        $result = $crawler->filter('span.invalid-feedback')->count();
        static::assertSame(2, $result);
    }

    public function testRegistrationWithPasswordTooShort()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Inscription')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton("S'inscrire")->form();
        $form['registration[username]'] = 'randomUsername';
        $form['registration[email]'] = 'randomEmail@gmail.com';
        $form['registration[password]'] = 'random';
        $form['registration[confirm_password]'] = 'random';

        $this->client->submit($form);
        static::assertSelectorTextContains('html span.form-error-message', 'Votre mot de passe doit faire minimum 8 caractères');
    }
}
