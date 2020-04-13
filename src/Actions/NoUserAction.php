<?php

namespace App\Actions;

use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageAction
 *
 * @Route("/nouser", name="app.no.user")
 */
final class NoUserAction
{
    public function __invoke(ViewResponderInterface $responder)
    {
        return $responder (
            'include/no_user_account.html.twig',
            [
            ]
        );
    }
}

