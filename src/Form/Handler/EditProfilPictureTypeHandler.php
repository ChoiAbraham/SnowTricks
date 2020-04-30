<?php

namespace App\Form\Handler;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\DTO\ProfilPictureDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Form\Handler\Interfaces\EditProfilPictureTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class EditProfilPictureTypeHandler implements EditProfilPictureTypeHandlerInterface
{
    /**
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /** @var EntityManagerInterface */
    private $em;

    /** @var FlashBagInterface */
    private $bag;

    /**
     * EditProfilPictureTypeHandler constructor.
     */
    public function __construct(UserBuilderInterface $userBuilder, UserRepositoryInterface $userRepository, EntityManagerInterface $em, FlashBagInterface $bag)
    {
        $this->userBuilder = $userBuilder;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->bag = $bag;
    }

    public function handle(FormInterface $form, User $userConnected): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ProfilPictureDTO $profilPictureDTO */
            $profilPictureDTO = $form->getData();

            /** @var User $user */
            $user = $this->userBuilder->updateProfilPicture($profilPictureDTO, $userConnected)->getUser();

            $this->bag->add('success', 'Votre avatar a été modifié');

            return true;
        }

        return false;
    }
}
