<?php


namespace App\Tests\Actions;


use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageActionFunctionalTest extends AbstractWebTestCase
{
    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');

        static::assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    }

    public function testHomepageTitleInHeaderIsPresent()
    {
        $crawler = $this->client->request('GET', '/');

        static::assertSame(1, $crawler->filter('html:contains("SnowTricks")')->count());
    }
}