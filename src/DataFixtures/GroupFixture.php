<?php

namespace App\DataFixtures;

use App\Domain\Entity\GroupTrick;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixture extends BaseFixture
{
    private static $groupTrickContents = [
        'Stances',
        'Straight airs',
        'Grabs',
        'Les rotations',
        'Flips',
        'Les rotations désaxées',
        'Slides',
        'Les one foot tricks',
        'Old School',
        'Autres'
    ];

    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; ++$i) {
            $groupTrick = new GroupTrick();
            $groupTrick->setname(self::$groupTrickContents[$i]);
            $manager->persist($groupTrick);

            $this->addReference(GroupTrick::class . '_' . $i, $groupTrick);
        }

        $manager->flush();
    }
}
