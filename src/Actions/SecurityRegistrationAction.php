<?php


namespace App\Actions;

use App\Domain\Entity\User;
use App\Form\Handler\Interfaces\AddUserTypeHandlerInterface;
use App\Form\Type\RegistrationType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityRegistrationAction
 *
 * @Route("/registration", name="registration")
 */
class SecurityRegistrationAction
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var AddUserTypeHandlerInterface */
    private $addUserTypeHandler;

    private $encoder;
    /**
     * SecurityRegistrationAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param AddUserTypeHandlerInterface $addUserTypeHandler
     */
    public function __construct(FormFactoryInterface $formFactory, AddUserTypeHandlerInterface $addUserTypeHandler)
    {
        $this->formFactory = $formFactory;
        $this->addUserTypeHandler = $addUserTypeHandler;
    }

    public function __invoke(Request $request, RedirectResponder $redirect, ViewResponder $responder)
    {
        $addUserType = $this->formFactory->create(RegistrationType::class)->handleRequest($request);

        if ($this->addUserTypeHandler->handle($addUserType)) {
            return $redirect('security_login');
        }

        return $responder (
            'security/registration.html.twig',
            [
                'form' => $addUserType->createView()
            ]
        );
    }
}