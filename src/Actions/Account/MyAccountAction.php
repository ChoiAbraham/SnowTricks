<?php


namespace App\Actions\Account;

use App\Actions\Interfaces\MyAccountActionInterface;
use App\Domain\Entity\Trick;
use App\Domain\Repository\UserRepository;
use App\Domain\Repository\TrickRepository;
use App\Form\Handler\Interfaces\EditProfilPictureTypeHandlerInterface;
use App\Form\Type\ProfilPictureType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

/**
 * Class MyAccountAction
 *
 * @Route("/dashboard", name="my_account", methods={"GET","POST"})
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
        $user = $this->security->getUser();
        $userId = $user->getId();

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