<?php

namespace App\Tests\Actions;

use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class HomepageActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

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

    public function testLetAuthenticatedUserToAccessHomepage()
    {
        $this->client->request('GET', '/');
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_0');
        $this->login($this->client, $user);

        static::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
