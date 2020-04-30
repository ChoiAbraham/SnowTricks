<?php

namespace App\Actions\Security;

use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Form\Handler\Interfaces\ResetPasswordTypeHandlerInterface;
use App\Form\Type\ResetPasswordType;
use App\Responders\Interfaces\ViewResponderInterface;
use App\Responders\RedirectResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reset_password/{token}", name="app.reset.password")
 */
class SecurityChangePasswordAction
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var FormFactoryInterface */
    protected $formFactory;

    /** @var ResetPasswordTypeHandlerInterface */
    private $resetPasswordTypeHandler;

    /**
     * SecurityChangePasswordAction constructor.
     */
    public function __construct(UserRepositoryInterface $userRepository, FormFactoryInterface $formFactory, ResetPasswordTypeHandlerInterface $resetPasswordTypeHandler)
    {
        $this->userRepository = $userRepository;
        $this->formFactory = $formFactory;
        $this->resetPasswordTypeHandler = $resetPasswordTypeHandler;
    }

    public function __invoke(Request $request, RedirectResponder $redirect, ViewResponderInterface $responder)
    {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['token' => $request->attributes->get('token')]);

        if (is_null($user)) {
            return $redirect('homepage_action');
        } else {
            $form = $this->formFactory->create(ResetPasswordType::class)->handleRequest($request);

            if ($this->resetPasswordTypeHandler->handle($form, $user)) {
                return $redirect('homepage_action');
            }

            return $responder(
                'security/form_reset_password.html.twig',
                [
                    'form' => $form->createView(),
                ]
            );
        }
    }
}
