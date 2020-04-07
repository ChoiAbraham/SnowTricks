<?php

namespace App\Actions;
use Symfony\Component\Routing\Annotation\Route;

class SecurityLogoutAction
{
    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        throw new \Exception('Will be intercepted before getting here');
    }
}