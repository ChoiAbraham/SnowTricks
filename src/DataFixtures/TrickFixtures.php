<?php

namespace App\DataFixtures;

use App\Domain\Entity\Comment;
use App\Domain\Entity\GroupTrick;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends BaseFixture implements DependentFixtureInterface
{
    private static $trickTitles = [
        'Why Asteroids Taste Like Bacon',
        'Life on Planet Mercury: Tan, Relaxing and Fabulous',
        'Light Speed Travel: Fountain of Youth or Fallacy',
    ];

    private static $trickContents = [
        'Content 1',
        'Content 2',
        'Content 3',
        'Content 4',
    ];

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Trick::class, 10, function (Trick $trick, $count) {
            $title = $trick->setTitle($this->faker->sentence($nbWords = 6, $variableNbWords = true));

            $trick->setSlug($title);
            $trick->setContent($this->faker->randomElement(self::$trickContents));
            $trick->setUpdatedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));

            $trick->setCreator($this->getRandomReference(User::class));

            $trick->setLastUser($this->getRandomReference(User::class));
            $trick->setGroupTrick($this->getRandomReference(GroupTrick::class));
        });

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixture::class,
            GroupFixture::class
        ];
    }
}
