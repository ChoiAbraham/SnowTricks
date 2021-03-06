<?php

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\UserDTOInterface;
use App\Form\CustomConstraints as AcmeAssert;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO implements UserDTOInterface
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @AcmeAssert\UniqueUsername()
     */
    protected $username;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email(message="Entrez une addresse Email")
     * @AcmeAssert\UniqueEmail()
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    protected $password;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\EqualTo(propertyPath="password", message="Mot de passe non identique")
     */
    private $confirm_password;

    /**
     * CreateUserDTO constructor.
     */
    public function __construct(string $name, string $email, string $password, string $confirm_password)
    {
        $this->username = $name;
        $this->email = $email;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getConfirmPassword(): string
    {
        return $this->confirm_password;
    }

    public function setConfirmPassword(string $confirm_password): void
    {
        $this->confirm_password = $confirm_password;
    }
}
