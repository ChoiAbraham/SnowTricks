<?php

namespace App\Tests\Actions\Account;

use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use App\Tests\NeedLoginTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MyAccountActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testMyAccountIsRedirected()
    {
        $this->client->request('GET', '/dashboard');

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
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

        $form = $crawler->selectButton('Envoyer')->form();

        $photo = new UploadedFile(
            'public/uploads/profile_picture/profil_picture_default.jpg',
            'profil_picture_default.jpg',
            'image/jpeg',
            null
        );

        $form['profil_picture[profilPicture]'] = $photo;

        $this->client->submit($form);

        $this->client->followRedirect();
        static::assertSelectorTextContains('html div.alert.alert-success', 'Votre avatar a été modifié');
    }
}
