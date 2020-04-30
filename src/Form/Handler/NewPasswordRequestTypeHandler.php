<?php

namespace App\Form\Handler;

use App\Domain\DTO\EmailPasswordRecoveryDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Form\Handler\Interfaces\NewPasswordRequestTypeHandlerInterface;
use App\Service\MailSenderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /** @var UrlGeneratorInterface */
    private $router;

    /**
     * NewPasswordRequestTypeHandler constructor.
     */
    public function __construct(FlashBagInterface $bag, MailSenderHelper $mailSenderHelper, UserRepositoryInterface $userRepository, EntityManagerInterface $em, UrlGeneratorInterface $router)
    {
        $this->bag = $bag;
        $this->mailSenderHelper = $mailSenderHelper;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->router = $router;
    }

    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var EmailPasswordRecoveryDTO $emailPasswordRecoveryDTO */
            $emailPasswordRecoveryDTO = $form->getData();

            $pseudo = $emailPasswordRecoveryDTO->getPseudo();
            $email = $emailPasswordRecoveryDTO->getEmail();

            /** @var User $user */
            $user = $this->userRepository->findOneBy(['name' => $pseudo, 'email' => $email]);

            if (is_null($user)) {
                $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');

                return new RedirectResponse($this->router->generate('homepage_action'));
            }

            $token = md5(uniqid(rand()));
            $user->setToken($token);

            $this->em->flush();

            $this->mailSenderHelper->sendTemplatedEmailForPasswordReset($token, $email, $pseudo);

            $this->bag->add('success', 'Un mail vous a été envoyé avec un lien pour modifier votre mot de passe');

            return true;
        }

        return false;
    }
}
