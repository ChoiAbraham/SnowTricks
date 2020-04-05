<?php

namespace App\Actions;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logout", name="app_logout")
 */
class SecurityLogoutAction
{
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }
}