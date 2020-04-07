<?php

namespace App\DataFixtures;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickVideo;
use App\Domain\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VideoTrickFixture extends BaseFixture implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $trickVideo = new TrickVideo($this->faker->url);
            $trickVideo->setName($this->faker->title);
            $this->addReference(TrickVideo::class . '_' . $i, $trickVideo);
            $manager->persist($trickVideo);

            $trickVideo->setTrick($this->getRandomReference(Trick::class));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [TrickFixtures::class];
    }
}
