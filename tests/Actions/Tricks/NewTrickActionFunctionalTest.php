<?php

namespace App\Tests\Actions\Tricks;

use App\DataFixtures\CommentFixture;
use App\DataFixtures\GroupFixture;
use App\DataFixtures\ImageTrickFixture;
use App\DataFixtures\TrickFixtures;
use App\DataFixtures\UserFixture;
use App\DataFixtures\VideoTrickFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class NewTrickActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testIsRedirectedIfNotAuthenticated()
    {
        $this->client->request('GET', '/trick/new');

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testLetAuthenticatedUserToAccessPage()
    {
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_0');
        $this->login($this->client, $user);

        $crawler = $this->client->request('GET', '/trick/new');
        static::assertFalse($this->client->getResponse()->isRedirection());
        static::assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testSuccessNewTrickAddedWithoutMedia()
    {
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();

        $this->loadFixtures([
            UserFixture::class,
            GroupFixture::class,
            TrickFixtures::class,
            ImageTrickFixture::class,
            VideoTrickFixture::class,
            CommentFixture::class,
        ]);

        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $crawler = $this->client->request('GET', '/trick/new');

        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertSelectorTextContains('h1', 'Nouveau Trick');

        $form = $crawler->selectButton('Ajouter le Trick')->form();

        $this->client->submit($form, [
            'create_trick[title]' => 'Title of the trick',
            'create_trick[content]' => 'content',
        ]);

        static::assertTrue($this->client->getResponse()->isRedirection());
        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('h1', 'Mon Compte');
    }
}
