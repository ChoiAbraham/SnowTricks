<?php

namespace App\DataFixtures;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        // For testing
        $commentTest = new Comment();
        $commentTest->setContent('hello');
        // set Trick Crail
        $commentTest->setTrick($this->getReference(Trick::class . '_' . 9, $commentTest));
        // set Abraham Choi
        $commentTest->setUser($this->getReference(User::class . '_' . 7, $commentTest));

        $manager->persist($commentTest);
        $manager->flush();

        $this->createMany(Comment::class, 100, function(Comment $comment) {
            $comment->setContent(
                $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
            );
            $comment->setTrick($this->getRandomReference(Trick::class));
            //$comment->setTrick($this->getReference(Trick::class.'_'.$this->faker->numberBetween(0, 9)));

            $comment->setUser($this->getRandomReference(User::class));
            //$comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [TrickFixtures::class,UserFixture::class];
    }
}
