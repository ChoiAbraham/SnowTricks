<?php


namespace App\Tests\Actions\Tricks;

use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\Response;

class NewTrickActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testMyAccountIsRedirectedIfNotAuthenticated()
    {
        $this->client->request('GET', '/trick/new');

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());

        static::assertSelectorTextContains('h2', 'Vous devez être connecté pour accéder à cette page');
    }

    public function testLetAuthenticatedUserToAccessPage()
    {
        $crawler = this->client->request('GET', '/trick/new');
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_0');
        $this->login($this->client, $user);

        static::assertSame(1, $crawler->filter('html:contains("Nouveau Trick")')->count());
        static::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSuccessNewTrickAddedToDatabaseWithCorrectData()
    {


    }

    public function testFailureFormSubmissionWithWrongData()
    {

    }
}