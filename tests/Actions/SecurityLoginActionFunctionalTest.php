<?php


namespace App\Tests\Actions;


use App\DataFixtures\UserFixture;
use App\Tests\AbstractWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class SecurityLoginActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

    public function testSuccessfulLogin()
    {
        $this->loadFixtures([UserFixture::class]);

        $csrfToken = $this->containerService->get('security.csrf.token_manager')->getToken('authenticate');
        $crawler = $this->client->request('GET', '/login');

        $form = $crawler->selectButton('Se Connecter')->form();
        $form['_csrf_token'] = $csrfToken;
        $form['username'] = 'Abraham Choi';
        $form['mail'] = 'abraham.choi@yahoo.fr';
        $form['password'] = 'abrahamchoi';

        $this->client->submit($form);

        static::assertResponseRedirects('/');
    }

    /*
     * @expectedException CustomUserMessageAuthenticationException
     */
    public function testLoginWithWrongUsername()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        $csrfToken = $this->containerService->get('security.csrf.token_manager')->getToken('authenticate');

        $form = $crawler->selectButton("Se Connecter")->form();
        //Wrong Username
        $form['_csrf_token'] = $csrfToken;
        $form['username'] = 'Abraham Choiiiiii';
        $form['mail'] = 'abraham.choi@yahoo.fr';
        $form['password'] = 'abrahamchoi';

        $this->client->submit($form);
//        $this->expectException(CustomUserMessageAuthenticationException::class);

        $this->client->followRedirect();

        static::assertSelectorTextContains('html div.alert-danger', 'Mauvais identifiant');
    }

    /*
     * @expectedException CustomUserMessageAuthenticationException
     */
    public function testLoginWithWrongEmail()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        $csrfToken = $this->containerService->get('security.csrf.token_manager')->getToken('authenticate');

        $form = $crawler->selectButton("Se Connecter")->form();
        $form['_csrf_token'] = $csrfToken;
        $form['username'] = 'Abraham Choi';
        //Wrong Email
        $form['mail'] = 'abraham.choi@yahoo.com';
        $form['password'] = 'abrahamchoi';

        $this->client->submit($form);
        $this->client->followRedirect();

        static::assertSelectorTextContains('html div.alert-danger', 'Mauvais identifiant');
    }

    /*
     * @expectedException CustomUserMessageAuthenticationException
     */
    public function testLoginWithWrongPassword()
    {
        $this->loadFixtures([UserFixture::class]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Connexion')->link();
        $crawler = $this->client->click($link);

        $csrfToken = $this->containerService->get('security.csrf.token_manager')->getToken('authenticate');

        $form = $crawler->selectButton("Se Connecter")->form();
        $form['_csrf_token'] = $csrfToken;
        $form['username'] = 'Abraham Choi';
        $form['mail'] = 'abraham.choi@yahoo.com';
        //Wrong Password
        $form['password'] = 'abrahamchoii';

        $this->client->submit($form);
        $this->client->followRedirect();

        static::assertSelectorTextContains('html div.alert-danger', 'Mauvais identifiant');
    }
}