<?php

namespace App\Tests\Actions\Tricks;

use App\DataFixtures\ImageTrickFixture;
use App\DataFixtures\TrickFixtures;
use App\DataFixtures\UserFixture;
use App\DataFixtures\VideoTrickFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;

class DeleteTrickActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testSuccessfulTrickDeleted()
    {
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();

        $this->loadFixtures([
            TrickFixtures::class,
            ImageTrickFixture::class,
            VideoTrickFixture::class,
        ]);

        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $trickRepository = $this->containerService->get('App\Domain\Repository\TrickRepository');
        $trick = $trickRepository->findOneBy(['slug' => 'crail']);
        $token = 'delete'.$trick->getId();

        $csrfStorage = $this->containerService->get('security.csrf.token_storage');
        $csrfStorage->setToken($token, 'random');

        $this->client->request(
            'POST',
            '/delete/crail',
            ['_token' => 'random',
            'slug' => 'crail', ]
        );

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.flash-homepage', 'La figure a été supprimé');
    }
}
