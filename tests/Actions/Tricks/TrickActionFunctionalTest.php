<?php

namespace App\Tests\Actions\Tricks;

use App\DataFixtures\ImageTrickFixture;
use App\DataFixtures\TrickFixtures;
use App\DataFixtures\VideoTrickFixture;
use App\Tests\AbstractWebTestCase;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class TrickActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

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
}