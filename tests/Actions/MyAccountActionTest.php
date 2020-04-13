<?php


namespace App\Tests\Actions;


use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MyAccountActionTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testMyAccountIsRedirected()
    {
        $this->client->request('GET', '/dashboard');

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());

        static::assertSelectorTextContains('h2', 'Vous devez être connecté pour accéder à cette page');
    }

    public function testLetAuthenticatedUserToAccessAccountPage()
    {
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_0');
        $this->login($this->client, $user);

        $this->client->request('GET', '/dashboard');

        static::assertFalse($this->client->getResponse()->isRedirection());
        static::assertTrue($this->client->getResponse()->isSuccessful());
    }


    public function testSuccessfulProfilPictureUpload()
    {
        //Simulation Authentification
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $crawler = $this->client->request('GET', '/dashboard');

        $form = $crawler->selectButton("Envoyer")->form();

        // Comment Upload a File in Test? // problème avec le path $this->uploadProfilPicturePath,
        // Question 2/ dois-je tester image size > 500k, si oui comment?
        $photo = new UploadedFile(
            $this->uploadProfilPicturePath,
            'profil_picture_test.jpg',
            'image/jpeg',
            null
        );
        $this->client->request(
            'POST',
            '/submit',
            ['name' => 'Abraham'],
            ['photo' => $photo]
        );

        // ou plutôt :
//      $form['profil_picture[profilPicture]'] = $photo;
//        $crawler = $this->client->submit($form);
//        $crawler = $this->client->followRedirect();
//        static::assertSelectorTextContains('html div.alert.alert-success', 'Votre avatar a été modifié');
    }
}