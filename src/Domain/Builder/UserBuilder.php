<?php


namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\DTO\CreateUserDTO;
use App\Domain\Entity\User;

class UserBuilder implements UserBuilderInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserBuilder constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param
     * @return UserBuilder
     */
    public function create(CreateUserDTO $dto): self
    {
        $this->user = new User(
            $dto->getUsername(),
            $dto->getEmail(),
            $dto->getPassword()
        );

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}