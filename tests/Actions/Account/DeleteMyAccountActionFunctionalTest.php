<?php

namespace App\Tests\Actions\Account;

use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class DeleteMyAccountActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testSuccessfulMyAccountDeleted()
    {
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $tokenName = 'delete-item';

        $csrfStorage = $this->containerService->get('security.csrf.token_storage');
        $csrfStorage->setToken($tokenName, 'random');

        $this->client->request(
            'POST',
            '/dashboard/delete-account/' . $user->getId(),
            [
                '_token' => 'random',
            ]
        );

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.flash-homepage', 'Votre compte a été supprimé');
    }
}