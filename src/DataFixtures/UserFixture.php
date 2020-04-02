<?php

namespace App\DataFixtures;

use App\Domain\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 21, function(User $user) {
            $user->setName($this->faker->name);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->encoder->encodePassword($user, $this->faker->password));
            //$user->setPicturePath($this->faker->image($dir = '/tmp', $width = 640, $height = 480);
            $user->setToken($this->faker->creditCardNumber);
        });

        $manager->flush();
    }
}
