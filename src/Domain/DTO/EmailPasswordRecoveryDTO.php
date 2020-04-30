<?php

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\EmailPasswordRecoveryDTOInterface;
use Symfony\Component\Validator\Constraints as Assert;

class EmailPasswordRecoveryDTO implements EmailPasswordRecoveryDTOInterface
{
    /**
     * @Assert\NotBlank(message="Vous devez spécifier le username")
     */
    protected $pseudo;

    /**
     * @Assert\NotBlank(message="Vous devez spécifier un email")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    protected $email;

    /**
     * EmailPasswordRecoveryDTO constructor.
     *
     * @param $pseudo
     * @param $email
     */
    public function __construct($pseudo, $email)
    {
        $this->pseudo = $pseudo;
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }
}
