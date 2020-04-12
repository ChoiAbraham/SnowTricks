<?php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    private static $userNames = [
        'Simone LeBatelier',
        'Karel Labonté',
        'Harbin Parent',
        'Pénélope Margand',
        'Vachel Quessy',
        'Yolande Vadeboncoeur',
        'Jacquenett Gousse',
        'Abraham Choi'
    ];

    private static $userEmails = [
        'SimoneLeBatelier@rhyta.com',
        'KarelLabonte@teleworm.us',
        'HarbinParent@armyspy.com',
        'PenelopeMargand@dayrep.com',
        'VachelQuessy@rhyta.com',
        'YolandeVadeboncoeur@jourrapide.com',
        'JacquenettGousse@dayrep.com',
        'abraham.choi@yahoo.fr'
    ];

    private static $userPasswords = [
        'SimoneLeBatelier',
        'KarelLabonte',
        'HarbinParent',
        'PenelopeMargand',
        'VachelQuessy',
        'YolandeVadeboncoeur',
        'JacquenettGousse',
        'abrahamchoi'
    ];

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $this->encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 8, function(User $user, $i) {
            $user->setName(self::$userNames[$i]);
            $user->setEmail(self::$userEmails[$i]);
            // Pour récupérer User instance in tests
            // voir doc liip bundle
            $this->setReference('userRef_' . $i, $user);

            $user->setPassword($this->encoder->encodePassword($user, self::$userPasswords[$i]));
            //$user->setPicturePath($this->faker->image($dir = '/tmp', $width = 640, $height = 480);
            $user->setToken($this->faker->creditCardNumber);
        });

        $manager->flush();
    }
}
