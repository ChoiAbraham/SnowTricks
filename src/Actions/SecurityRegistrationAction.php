<?php


namespace App\Actions;

use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SecurityRegistrationAction
 *
 * @Route("/registration", name="registration")
 */
class SecurityRegistrationAction
{
    public function __invoke(RedirectResponder $redirect, ViewResponder $responder)
    {
        // 1. create Form
        // 2. Handle Form : redirect to login


        return $responder (
            'security/registration.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}