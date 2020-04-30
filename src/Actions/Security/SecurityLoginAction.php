<?php

namespace App\Actions\Security;

use App\Responders\ViewResponder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class SecurityLoginAction.
 *
 * @Route("/login", name="security_login")
 */
final class SecurityLoginAction
{
    public function __invoke(AuthenticationUtils $authenticationUtils, ViewResponder $responder)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $responder(
            'security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }
}
