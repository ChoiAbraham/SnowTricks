<?php


namespace App\Actions;

use App\Actions\Interfaces\SecurityRequestNewPasswordInterface;
use App\Form\Handler\Interfaces\NewPasswordRequestTypeHandlerInterface;
use App\Form\Type\EmailForPasswordRecoveryFormType;
use App\Responders\Interfaces\ViewResponderInterface;
use App\Responders\RedirectResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/password-recovery", name="request.reset.password")
 */
class SecurityRequestNewPasswordAction implements SecurityRequestNewPasswordInterface
{
    /** @var FormFactoryInterface $formFactory */
    protected $formFactory;

    /** @var NewPasswordRequestTypeHandlerInterface */
    private $newPasswordRequestTypeHandler;

    /**
     * SecurityPasswordRecoveryAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param NewPasswordRequestTypeHandlerInterface $newPasswordRequestTypeHandler
     */
    public function __construct(FormFactoryInterface $formFactory, NewPasswordRequestTypeHandlerInterface $newPasswordRequestTypeHandler)
    {
        $this->formFactory = $formFactory;
        $this->newPasswordRequestTypeHandler = $newPasswordRequestTypeHandler;
    }

    public function __invoke(Request $request, RedirectResponder $redirect, ViewResponderInterface $responder)
    {
        $emailNewPassword = $this->formFactory->create(EmailForPasswordRecoveryFormType::class)->handleRequest($request);

        if ($this->newPasswordRequestTypeHandler->handle($emailNewPassword)) {
            return $redirect('homepage_action');
        }

        return $responder (
            'security/form_request_new_password.html.twig',
            [
                'form' => $emailNewPassword->createView()
            ]
        );
    }
}