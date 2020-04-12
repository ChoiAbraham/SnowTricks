<?php


namespace App\Actions;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/reset_password/{slug}", name="app.reset.password")
 */
class SecurityChangePasswordAction
{
    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var UserRepositoryInterface */
    private $userRepository;

    /**
     * SecurityChangePasswordAction constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepositoryInterface $userRepository)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder)
    {
//        /** @var User $user */
//        $user = $this->userRepository->findOneBy(['token' => $request->attributes->get('slug')]);
//
//        return $responder (
//            'security/resetpassword.html.twig',
//            [
//                'form' => $form->createView()
//            ]
//        );
//        } else {
////            return $redirect('homepage_action');
//        }
    }
}