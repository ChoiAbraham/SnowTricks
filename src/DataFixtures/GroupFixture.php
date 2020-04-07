<?php

namespace App\DataFixtures;

use App\Domain\Entity\GroupTrick;
use App\Domain\Entity\Trick;
use Doctrine\Common\Persistence\ObjectManager;

class GroupFixture extends BaseFixture
{
    private static $groupTrickContents = [
        'group1',
        'group2',
        'group3',
    ];

    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 3; $i++) {
            $groupTrick = new GroupTrick();
            $groupTrick->setname(self::$groupTrickContents[$i]);
            $manager->persist($groupTrick);

            $this->addReference(GroupTrick::class . '_' . $i, $groupTrick);
        }

        $manager->flush();
    }
}
