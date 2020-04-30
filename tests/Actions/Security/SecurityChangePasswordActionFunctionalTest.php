<?php

namespace App\Tests\Actions\Security;

use App\DataFixtures\Tests\UserPasswordFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class SecurityChangePasswordActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

    public function testSuccessfulPasswordChanged()
    {
        $this->loadFixtures([UserPasswordFixture::class]);

        $users = $this->loadFixtures([UserPasswordFixture::class])->getReferenceRepository();
        /** @var User $user */
        $user = $users->getReference('userRef_0');

        $token = $user->getToken();
        $uri = '/reset_password/'.$token;
        $crawler = $this->client->request('GET', $uri);

        $form = $crawler->selectButton('Réinitialiser')->form();

        $form['reset_password[username]'] = 'Abraham Choi';
        $form['reset_password[password][first]'] = 'newpassword6';
        $form['reset_password[password][second]'] = 'newpassword6';
        $this->client->submit($form);

        // 6. Assertion
        static::assertTrue($this->client->getResponse()->isRedirection());
        $this->client->followRedirect();

        static::assertTrue($this->client->getResponse()->isSuccessful());

        static::assertSelectorTextContains('.flash-homepage', 'Votre mot de passe été modifié avec succès');
    }
}
