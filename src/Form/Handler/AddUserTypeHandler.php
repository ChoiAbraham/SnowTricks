<?php


namespace App\Form\Handler;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\DTO\CreateUserDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Form\Handler\Interfaces\AddUserTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AddUserTypeHandler implements AddUserTypeHandlerInterface
{
    /**
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var FlashBagInterface
     */
    private $bag;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /** @var EntityManagerInterface */
    private $em;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * AddUserTypeHandler constructor.
     * @param UserBuilderInterface $userBuilder
     * @param FlashBagInterface $bag
     * @param UserRepositoryInterface $userRepository
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $this->encoder
     */
    public function __construct(UserBuilderInterface $userBuilder, FlashBagInterface $bag, UserRepositoryInterface $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->userBuilder = $userBuilder;
        $this->bag = $bag;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateUserDTO $data */
            $data = $form->getData();

            /** @var User $user */
            $user = $this->userBuilder->create($form->getData())->getUser();
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $this->em->persist($user);
            $this->em->flush();

            $this->bag->add('success', 'Succès: vous êtes inscrit');

            return true;
        }

        return false;
    }
}