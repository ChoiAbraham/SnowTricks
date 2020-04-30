<?php

namespace App\Actions\Comments;

use App\Actions\Interfaces\DeleteCommentActionInterface;
use App\Domain\Entity\Comment;
use App\Domain\Repository\Interfaces\CommentRepositoryInterface;
use App\Responders\RedirectResponder;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class DeleteCommentAction
 *
 * @Route("/delete/comment/{id}", name="delete.comment")
 * @IsGranted("ROLE_USER")
 */
final class DeleteCommentAction implements DeleteCommentActionInterface
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @var TokenStorageInterface *
     */
    private $tokenStorage;

    /** @var CommentRepositoryInterface */
    private $commentRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

//    /** @var string */
//    private $uploadPath;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrf;

    /**
     * DeleteCommentAction constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param CommentRepositoryInterface $commentRepository
     * @param FlashBagInterface $flashBag
     * @param CsrfTokenManagerInterface $csrf
     */
    public function __construct(EntityManagerInterface $manager, TokenStorageInterface $tokenStorage, CommentRepositoryInterface $commentRepository, FlashBagInterface $flashBag, CsrfTokenManagerInterface $csrf)
    {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->commentRepository = $commentRepository;
        $this->flashBag = $flashBag;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, RedirectResponder $redirect)
    {
        /** @var Comment $commentEntity */
        $commentEntity = $this->commentRepository->findOneBy(['id' => $request->attributes->get('id')]);
        $slug = $commentEntity->getTrick()->getSlug();

        if ($this->csrf->isTokenValid(new CsrfToken('delete' . $commentEntity->getId(), $request->get('_token')))) {
            if ($this->tokenStorage->getToken()->getUser()) {
                $this->manager->remove($commentEntity);
                $this->manager->flush();

                $this->flashBag->add('success', 'Le commentaire a été supprimé');
                return $redirect('trick_action', ['slug' => $slug]);
            }
        }

        return $redirect('homepage_action');
    }
}