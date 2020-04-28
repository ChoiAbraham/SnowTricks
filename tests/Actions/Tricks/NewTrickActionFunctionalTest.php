<?php


namespace App\Tests\Actions\Tricks;

use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\NeedLoginTrait;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Panther\PantherTestCase;

class NewTrickActionFunctionalTest extends PantherTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var ContainerInterface */
    protected $containerService;

    protected function setUp()
    {
        $this->client = self::createclient();

        $this->containerService = self::$container;
        $this->entityManager = $this->containerService->get('doctrine.orm.default_entity_manager');

        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->dropSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
        $schemaTool->createSchema($this->entityManager->getMetadataFactory()->getAllMetadata());
    }

    public function testMyAccountIsRedirectedIfNotAuthenticated()
    {
        $panther = static::createPantherClient();
        $panther->request('GET', '/trick/new');

        static::assertTrue($this->pantherClient->getResponse()->isRedirection());

        $this->pantherClient->followRedirect();
        static::assertTrue($this->pantherClient->getResponse()->isSuccessful());

        static::assertSelectorTextContains('h2', 'Vous devez être connecté pour accéder à cette page');
    }

    public function testLetAuthenticatedUserToAccessPage()
    {
        $panther = static::createPantherClient();

        $crawler = $panther->request('GET', '/trick/new');
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_0');
        $this->login($this->client, $user);

        static::assertSame(1, $crawler->filter('html:contains("Nouveau Trick")')->count());
        static::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testSuccessNewTrickAddedWithNoFirstImage()
    {
        $pantherClient = static::createPantherClient();

        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $crawler = $pantherClient->request('GET', '/trick/new');

        static::assertResponseIsSuccessful();

        $form = $crawler->selectButton("Ajouter le Trick")->form();

        $form['create_trick[title]'] = 'trick title';
        $form['create_trick[content]'] = 'content trick';

        $this->pantherClient->submit($form);

        static::assertTrue($pantherClient->getResponse()->isRedirection());
        $crawler = $pantherClient->followRedirect();
        static::assertTrue($pantherClient->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.alert-success', 'Votre Trick a été ajouté sur votre compte. Pour l\'ajouter à la liste des tricks, ajouter une image');
    }

    public function testSuccessNewTrickAddedWithFirstImage()
    {
        $pantherClient = static::createPantherClient();
        $client = static::createClient();
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_7');
        $this->login($client, $user);

        $crawler = $pantherClient->request('GET', '/trick/new');
        static::assertTrue($pantherClient->getResponse()->isSuccessful());
    }

    public function testPanther()
    {
        $pantherClient->executeScript("document.querySelector('#image_list').click()");

        $pantherClient->getMouse()->clickTo('.js-test');

        $photo = new UploadedFile(
            'public/uploads/first_image_default/first_image_default.jpg',
            'first_image_default.jpg',
            'image/jpeg',
            null
        );

        $form = $crawler->selectButton('Ajouter le Trick')->form(
            [
                'create_trick[title]' => 'tricktitle',
                'create_trick[content]' => 'content trick',
                'create_trick[imageslinks][0][image]' => $photo,
                'create_trick[imageslinks][0][alt]' => 'alt photo',
            ]
        );

        $pantherClient->submit($form);

        $pantherClient->waitFor('.flash-homepage');

        static::assertTrue($client->getResponse()->isRedirection());
        $pantherClient->followRedirect();
        static::assertTrue($client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.flash-homepage', 'Votre Trick a bien été ajouté');
    }
}