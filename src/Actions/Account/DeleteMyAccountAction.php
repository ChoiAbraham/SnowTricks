<?php


namespace App\Actions\Account;

use App\Actions\Interfaces\DeleteMyAccountActionInterface;
use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Domain\Repository\TrickRepository;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class MyAccountAction
 *
 * @Route("/dashboard/delete-account/{id}", name="my_account_delete")
 * @IsGranted("ROLE_USER")
 */
class DeleteMyAccountAction implements DeleteMyAccountActionInterface
{
    /** @var UserRepositoryInterface */
    private $userRepository;

    /** @var Security */
    private $security;

    /** @var FlashBagInterface */
    private $bag;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UploaderHelper */
    private $uploaderHelper;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrf;

    /** @var TokenStorageInterface */
    private $tokenStorageInterface;

    /** @var TrickRepository */
    private $trickRepository;

    /** @var CommentRepository */
    private $commentRepository;

    /**
     * DeleteMyAccountAction constructor.
     * @param UserRepositoryInterface $userRepository
     * @param Security $security
     * @param FlashBagInterface $bag
     * @param EntityManagerInterface $entityManager
     * @param UploaderHelper $uploaderHelper
     * @param CsrfTokenManagerInterface $csrf
     * @param TokenStorageInterface $tokenStorageInterface
     * @param TrickRepository $trickRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(UserRepositoryInterface $userRepository, Security $security, FlashBagInterface $bag, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper, CsrfTokenManagerInterface $csrf, TokenStorageInterface $tokenStorageInterface, TrickRepository $trickRepository, CommentRepository $commentRepository)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->bag = $bag;
        $this->entityManager = $entityManager;
        $this->uploaderHelper = $uploaderHelper;
        $this->csrf = $csrf;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->trickRepository = $trickRepository;
        $this->commentRepository = $commentRepository;
    }

    public function __invoke(Request $request, RedirectResponder $redirect, ViewResponder $responder)
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $userFromRepo = $this->userRepository->findOneBy(['id' => $request->attributes->get('id') ]);

        $submittedToken = $request->get('_token');

        if ($this->csrf->isTokenValid(new CsrfToken('delete-item', $submittedToken)) && $userFromRepo == $user) {

            $this->uploaderHelper->deleteProfilPictureImage($user->getPicturePath());

            $comments = $this->commentRepository->findBy(['user' => $user->getId()]);
            foreach ($comments as $comment) {
                /** @var Comment $comment */
                $comment->setUser(null);
            }
            $trickEntitys = $this->trickRepository->findBy(['creator' => $user->getId()]);
            foreach ($trickEntitys as $trick) {
                /** @var Trick $trick */
                $trick->setCreator(null);
                $trick->setLastUser(null);
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->bag->add('success', 'Votre compte a été supprimé');
            $this->tokenStorageInterface->setToken(null);

            // Error
            return $redirect('homepage_action');
        }

        return $redirect('homepage_action');
    }
}