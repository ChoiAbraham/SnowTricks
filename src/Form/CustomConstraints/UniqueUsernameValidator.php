<?php

namespace App\Form\CustomConstraints;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueUsernameValidator extends ConstraintValidator
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UniqueUsernameValidator constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueUsername) {
            throw new UnexpectedTypeException($constraint, UniqueUsername::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $userByUsername = $this->userRepository->findOneBy(['name' => $value]);

        if (null != $userByUsername) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
