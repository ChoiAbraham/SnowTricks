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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class EditTrickActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testIsRedirectedIfNotAuthenticated()
    {
        $this->loadFixtures([
            TrickFixtures::class,
            ImageTrickFixture::class,
            VideoTrickFixture::class,
        ]);

        $crawler = $this->client->request('GET', '/trick/edit/crail');

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testSuccessEditWithNewPicturesNewVideos()
    {
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_0');
        $this->login($this->client, $user);

        $this->loadFixtures([
            GroupFixture::class,
            TrickFixtures::class,
            ImageTrickFixture::class,
            VideoTrickFixture::class,
            CommentFixture::class
        ]);

        $crawler = $this->client->request('GET', '/trick/edit/crail');

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());

//        static::assertSame(1, $crawler->filter('html:contains("Le Trick a été modifié avec succès")')->count());
        static::assertSelectorTextContains('.fash-homepage', 'Le Trick a été modifié avec succès');
    }

    public function testSuccessEditWithDeletePicturesAndVideos()
    {
    }

    public function testSuccessEditWithChangingPicturesAndVideos()
    {
    }

    public function testPanther()
    {
        $pantherClient->executeScript("document.querySelector('#image_list').click()");
    }
}