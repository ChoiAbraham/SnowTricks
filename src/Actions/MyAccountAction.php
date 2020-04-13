<?php


namespace App\Actions;

use App\Actions\Interfaces\MyAccountActionInterface;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\TrickRepository;
use App\Form\Handler\Interfaces\EditProfilPictureTypeHandlerInterface;
use App\Form\ProfilPictureType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\UsageTrackingTokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class MyAccountAction
 *
 * @Route("/dashboard", name="my_account")
 * @IsGranted("ROLE_USER")
 */
class MyAccountAction implements MyAccountActionInterface
{
    /** @var TrickRepository */
    private $trickRepository;

    /** @var UserRepository */
    private $userRepository;

    /** @var Security */
    private $security;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @var EditProfilPictureTypeHandlerInterface
     */
    private $editProfilPictureTypeHandler;

    /** @var FormFactoryInterface */
    private $formFactory;

    /**
     * MyAccountAction constructor.
     * @param TrickRepository $trickRepository
     * @param UserRepository $userRepository
     * @param Security $security
     * @param TokenStorageInterface $tokenStorage
     * @param EditProfilPictureTypeHandlerInterface $editProfilPictureTypeHandler
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(TrickRepository $trickRepository, UserRepository $userRepository, Security $security, TokenStorageInterface $tokenStorage, EditProfilPictureTypeHandlerInterface $editProfilPictureTypeHandler, FormFactoryInterface $formFactory)
    {
        $this->trickRepository = $trickRepository;
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->tokenStorage = $tokenStorage;
        $this->editProfilPictureTypeHandler = $editProfilPictureTypeHandler;
        $this->formFactory = $formFactory;
    }


    public function __invoke(Request $request, ViewResponder $responder, RedirectResponder $redirect)
    {
        // authorization avec ROLE_USER
        // j'aimerais get the user from the token mais getToken() ne renvoie rien
        // question : je pensais qu'à chaque authentification, symfony stockait un token. comment je fais pour le récupérer?
//        dd($this->tokenStorageInterface->getToken());
        //getToken()->getUser doessn't worl => service

        $userId = $this->security->getUser()->getId();
        $user = $this->security->getUser();
        /** @var Trick $tricks */
        $tricks = $this->trickRepository->findBy(['creator' => $userId]);

        $form = $this->formFactory->create(ProfilPictureType::class)->handleRequest($request);

        if ($this->editProfilPictureTypeHandler->handle($form, $user)) {
            return $redirect('my_account');
        }

        return $responder(
            'account/account_dashboard.html.twig',
            [
                'form' => $form->createView(),
                'tricks' => $tricks,
            ]
        );
    }
}