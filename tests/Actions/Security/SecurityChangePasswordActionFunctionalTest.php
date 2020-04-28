<?php

namespace App\Tests\Actions\Security;

use App\DataFixtures\UserFixture;
use App\Domain\Entity\User;
use App\Tests\AbstractWebTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Component\DomCrawler\Crawler;

class SecurityChangePasswordActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;

    public function testSuccessfulPasswordChanged()
    {
        $this->loadFixtures([UserFixture::class]);

        // 1.Take the eighth user from the database / Abraham Choi <abraham.choi@yahoo.fr>
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();
        /** @var User $user */
        $user = $users->getReference('userRef_7');

        // 2. Set a token
        $token = md5(uniqid(rand()));
        $user->setToken($token);

        // 3. update the token in the database // NOT WORKING
        $this->entityManager->flush();
        dd($user);

        // 4. go to the change password page
        $uri = '/reset_password/' . $token;
        $crawler = $this->client->request('GET', $uri);

        // 5. Submit form
        $button = $crawler->selectButton('Réinitialiser');
        $form = $button->form();
        $form['email_for_password_recovery_form[pseudo]']->setValue('Abraham CHOI');
        $form['email_for_password_recovery_form[password]']->setValue(('Hello World'));
        $this->client->submit($form);

        // 6. Assertion
        static::assertResponseRedirects('/');
        $this->client->followRedirect();

        static::assertSelectorTextContains('flash-homepage', 'Votre mot de passe été modifié avec succès');
    }
}