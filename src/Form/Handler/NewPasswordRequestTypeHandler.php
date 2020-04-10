<?php

namespace App\Form\Handler;

use App\Domain\Entity\User;
use App\Form\Handler\Interfaces\NewPasswordRequestTypeHandlerInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Service\MailSenderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class NewPasswordRequestTypeHandler implements NewPasswordRequestTypeHandlerInterface
{
    /** @var FlashBagInterface */
    private $bag;

    /** @var MailSenderHelper */
    private $mailSenderHelper;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * NewPasswordRequestTypeHandler constructor.
     * @param FlashBagInterface $bag
     * @param MailSenderHelper $mailSenderHelper
     * @param UserRepositoryInterface $userRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(FlashBagInterface $bag, MailSenderHelper $mailSenderHelper, UserRepositoryInterface $userRepository, EntityManagerInterface $em)
    {
        $this->bag = $bag;
        $this->mailSenderHelper = $mailSenderHelper;
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var User $user */
            $user = $this->userRepository->findOneBy(['name' => $data['pseudo'], 'email' => $data['email']]);
            if (!$user) {
                throw new CustomUserMessageAuthenticationException('Votre requête n\'a pu être abouti');
//                return new RedirectResponse($this->router->generate('homepage_action'));
            }
            $token = md5(uniqid(rand()));
            $user->setToken($token);

            $this->em->persist($user);
            $this->em->flush();

            $this->mailSenderHelper->sendEmailWithTemplateTwig($token, $data['email'], $data['pseudo']);

            $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');
            return true;
        }

        return false;
    }
}
