<?php

namespace App\DataFixtures\Tests;

use App\Domain\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordFixture extends BaseTestFixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    private static $userNames = [
        'Abraham Choi',
    ];

    private static $userEmails = [
        'abraham.choi@yahoo.fr',
    ];

    private static $userPasswords = [
        'abrahamchoi',
    ];

    /**
     * UserFixture constructor.
     *
     * @param UserPasswordEncoderInterface $this->encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 1, function (User $user, $i) {
            $user->setName(self::$userNames[$i]);
            $user->setEmail(self::$userEmails[$i]);

            $token = md5(uniqid(rand()));
            $user->setToken($token);
            $this->setReference('userRef_' . $i, $user);

            $user->setPassword($this->encoder->encodePassword($user, self::$userPasswords[$i]));
        });

        $manager->flush();
    }
}
