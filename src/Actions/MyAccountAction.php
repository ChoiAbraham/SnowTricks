<?php


namespace App\Actions;

use App\Domain\Entity\Trick;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\TrickRepository;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Class MyAccountAction
 *
 * @Route("/dashboard", name="my_account")
 */
class MyAccountAction
{
    /**
     *
     *
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     *
     *
     * @var UserRepository
     */
    private $userRepository;

    public function __invoke(UserInterface $user = null, ViewResponder $responder, RedirectResponder $redirect)
    {
        if ($user == null) {
            return $responder (
                'include/no_user_account.html.twig',
                [
                ]
            );
        }

        /** @var Trick $tricks */
        $tricks = $this->trickRepository->findBy(['user' => $user->getId()]);

        // 1. get the User from the Session (tokenstorage) token
        // 2. if $user == User from the Session
        //    Then render the form form for Update ProfilPicture
        //    handlerForm :
        //         update ProfilPicture
        //         return redirectResponse sur my account dashboard
        //    render twig dashboard template
        // otherwise redirect to homepage
        /*
        if($user) {
            return $responder (
                'trick/trick_display.html.twig',
                [
                    'slug' => $slug,
                    'trick' => $trickRepository
                ]
            );
        }
        */

        return $redirect('homepage_action');
    }
}