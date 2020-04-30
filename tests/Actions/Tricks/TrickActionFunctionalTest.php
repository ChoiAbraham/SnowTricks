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

class TrickActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testTrickPageIsUp()
    {
        $this->loadFixtures([
            TrickFixtures::class,
            ImageTrickFixture::class,
            VideoTrickFixture::class,
        ]);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Crail')->link();
        $crawler = $this->client->click($link);

        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());

        static::assertSelectorTextContains('h1', 'Crail');
        static::assertSame(1, $crawler->filter('html:contains("Description")')->count());
    }

    public function testAddNewComment()
    {
        // Step 1 : Load All Fixtures
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

        // Step 2 : Simulation Authentification Abraham Choi
        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $crawler = $this->client->request('GET', '/');

        $link = $crawler->selectLink('Crail')->link();
        $crawler = $this->client->click($link);

        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
        static::assertSelectorTextContains('h1', 'Crail');

        $form = $crawler->selectButton('Envoyer')->form();

        $this->client->submit($form, [
            'trick_comment[text]' => 'New Comment',
        ]);

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.flash-homepage', 'Votre commentaire a été ajouté');
    }
}
