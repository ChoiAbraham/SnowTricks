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

    public function testSuccessEditChangePicture()
    {
        // Step 1 : Load All Fixtures
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();

        $this->loadFixtures([
            UserFixture::class,
            GroupFixture::class,
            TrickFixtures::class,
            ImageTrickFixture::class,
            CommentFixture::class,
        ]);

        // Step 2 : Simulation Authentification Abraham Choi
        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $crawler = $this->client->request('GET', '/trick/edit/crail');

        $form = $crawler->selectButton('Sauvegarder')->form();

        $photo = new UploadedFile(
            'public/uploads/first_image_default/first_image_default.jpg',
            'first_image_default.jpg',
            'image/jpeg',
            null
        );

        $form['update_trick[title]'] = 'Crail Updated';
        $form['update_trick[content]'] = 'New Content';
        //Change Picture 1
        $form['update_trick[imageslinks][0][image]'] = $photo;
        $form['update_trick[imageslinks][0][alt]'] = 'new photo';
        //TODO - Js Testing - Panther - Click and open 'New Video Field'
//        $form['update_trick[videoslinks][0][pathUrl]'] = 'https://www.youtube.com/watch?v=__l6q-9OVhY';

        $crawler = $this->client->submit($form);

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.flash-homepage', 'Le Trick a été modifié avec succès');
    }
}
