<?php


namespace App\Tests\Actions;

use App\DataFixtures\UserFixture;
use App\Tests\AbstractWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class SecurityRegistrationActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

    public function testSuccessAddNewUser()
    {
        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Inscription')->link();
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton("S'inscrire")->form();
        $form['registration[username]'] = 'abraham';
        $form['registration[email]'] = 'abraham@gmail.com';
        $form['registration[password]'] = 'abrahamchoi';
        $form['registration[confirm_password]'] = 'abrahamchoi';

        $this->client->submit($form);

        static::assertSelectorNotExists('.invalid-feedback');
        static::assertResponseRedirects('/login');

        $crawler = $this->client->followRedirect();
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