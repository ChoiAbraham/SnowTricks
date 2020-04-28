<?php


namespace App\Form\CustomConstraints;


use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class UniqueTrickTitleValidator extends ConstraintValidator
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * UniqueTrickTitleValidator constructor.
     * @param TrickRepositoryInterface $trickRepository
     */
    public function __construct(TrickRepositoryInterface $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof UniqueTrickTitle) {
            throw new UnexpectedTypeException($constraint, UniqueTrickTitle::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $userByUsername = $this->trickRepository->findOneBy(array('title' => $value));

        if ($userByUsername != null) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

}