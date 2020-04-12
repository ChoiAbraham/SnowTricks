<?php


namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\ChangePasswordDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\CustomConstraints as AcmeAssert;

class ChangePasswordDTO implements ChangePasswordDTOInterface
{
    /**
     * @var string
     * @AcmeAssert\NotInDatabase()
     * @Assert\NotBlank(message="Vous devez spécifier un username")
     */
    protected $username;

    /**
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*\d).{6,}$/i", message="Le nouveau mot de passe doit contenir au moins une lettre, un nombre et de longueur 6.")
     * @Assert\NotBlank(message="Vous devez spécifier un mot de passe")
     */
    protected $password;

    /**
     * ChangePasswordDTO constructor.
     * @param $username
     * @param $password
     */
    public function __construct(?string $username, ?string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}