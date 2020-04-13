<?php

namespace App\Form\Handler;

use App\Domain\DTO\ChangePasswordDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Form\Handler\Interfaces\ResetPasswordTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class ResetPasswordTypeHandler implements ResetPasswordTypeHandlerInterface
{
    /** @var FlashBagInterface */
    private $bag;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var EntityManagerInterface */
    private $em;

    /** @var UrlGeneratorInterface */
    private $router;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /**
     * ResetPasswordTypeHandler constructor.
     * @param FlashBagInterface $bag
     * @param UserRepositoryInterface $userRepository
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $router
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(FlashBagInterface $bag, UserRepositoryInterface $userRepository, EntityManagerInterface $em, UrlGeneratorInterface $router, UserPasswordEncoderInterface $encoder)
    {
        $this->bag = $bag;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->router = $router;
        $this->encoder = $encoder;
    }

    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ChangePasswordDTO $data */
            $data = $form->getData();

            /** @var User $user */
            $user = $this->userRepository->findOneBy(['name' => $data->getUsername()]);

            if(is_null($user)) {
                throw new \Exception('mauvais identifiant');
            } else {
                $hash = $this->encoder->encodePassword($user, $data->getPassword());
                $user->setToken(null);
                $user->setPassword($hash);

                $this->em->flush();

                $this->bag->add('success', 'Votre mot de passe été modifié avec succès');

                return true;
            }
        }

        return false;
    }
}
