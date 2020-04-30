<?php

namespace App\Tests\Actions\Comment;

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

class DeleteCommentActionFunctionalTest extends AbstractWebTestCase
{
    use FixturesTrait;
    use NeedLoginTrait;

    public function testSuccessfulCommentUpdate()
    {
        // Step 1 : Load All Fixtures
        /** @var User $user */
        $users = $this->loadFixtures([UserFixture::class])->getReferenceRepository();

        $this->loadFixtures([
            UserFixture::class,
            GroupFixture::class,
            TrickFixtures::class,
            ImageTrickFixture::class,
            VideoTrickFixture::class,
            CommentFixture::class,
        ]);

        // Step 2 : Simulation Authentification Abraham Choi
        $user = $users->getReference('userRef_7');
        $this->login($this->client, $user);

        $commentRepository = $this->containerService->get('App\Domain\Repository\CommentRepository');
        $comment = $commentRepository->findOneBy(['content' => 'hello']);
        $token = 'delete'.$comment->getId();

        $csrfStorage = $this->containerService->get('security.csrf.token_storage');
        $csrfStorage->setToken($token, 'random');

        $this->client->request(
            'POST',
            '/delete/comment/'.$comment->getId(),
            ['_token' => 'random',
                'slug' => 'crail', ]
        );

        static::assertTrue($this->client->getResponse()->isRedirection());

        $this->client->followRedirect();
        static::assertTrue($this->client->getResponse()->isSuccessful());
        static::assertSelectorTextContains('.flash-homepage', 'Le commentaire a été supprimé');
    }
}
