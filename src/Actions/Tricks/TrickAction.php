<?php

namespace App\Actions\Tricks;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickImage;
use App\Domain\Entity\TrickVideo;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\GroupTrickRepository;
use App\Domain\Repository\TrickImageRepository;
use App\Domain\Repository\TrickRepository;
use App\Domain\Repository\TrickVideoRepository;
use App\Form\Handler\Interfaces\EditTrickTypeHandlerInterface;
use App\Form\Handler\TrickCommentTypeHandler;
use App\Form\Type\TrickCommentType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class TrickDisplay.
 *
 * @Route("/trick/{slug}", name="trick_action")
 */
class TrickAction
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var trickRepository */
    protected $trickRepository;

    /** @var TrickImageRepository */
    protected $trickImageRepository;

    /** @var TrickVideoRepository */
    protected $trickVideoRepository;

    /** @var Security */
    private $security;

    /** @var CommentRepository */
    protected $commentRepository;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var GroupTrickRepository */
    protected $groupTrickRepository;

    /** @var EditTrickTypeHandlerInterface */
    private $editTrickTypeHandler;

    /** @var TrickCommentTypeHandler */
    private $trickCommentTypeHandler;

    /** @var UploaderHelper */
    private $uploaderHelper;

    /** @var FlashBagInterface */
    private $bag;

    /**
     * TrickAction constructor.
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, TrickRepository $trickRepository, TrickImageRepository $trickImageRepository, TrickVideoRepository $trickVideoRepository, Security $security, CommentRepository $commentRepository, FormFactoryInterface $formFactory, GroupTrickRepository $groupTrickRepository, EditTrickTypeHandlerInterface $editTrickTypeHandler, TrickCommentTypeHandler $trickCommentTypeHandler, UploaderHelper $uploaderHelper, FlashBagInterface $bag)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->trickRepository = $trickRepository;
        $this->trickImageRepository = $trickImageRepository;
        $this->trickVideoRepository = $trickVideoRepository;
        $this->security = $security;
        $this->commentRepository = $commentRepository;
        $this->formFactory = $formFactory;
        $this->groupTrickRepository = $groupTrickRepository;
        $this->editTrickTypeHandler = $editTrickTypeHandler;
        $this->trickCommentTypeHandler = $trickCommentTypeHandler;
        $this->uploaderHelper = $uploaderHelper;
        $this->bag = $bag;
    }

    public function __invoke(Request $request, ViewResponder $responder, RedirectResponder $redirect)
    {
        // TRICK PAGE
        /** @var Trick $trick */
        $trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        if (is_null($trick)) {
            throw new EntityNotFoundException('Pas de Trick "%s"', $trick->getSlug());
        }

        /** @var TrickImage $image */
        $images = $this->trickImageRepository->findBy(['trick' => $trick->getId()]);

        /** @var TrickVideo $video */
        $videos = $this->trickVideoRepository->findBy(['trick' => $trick->getId()]);

        $firstImage = $this->trickImageRepository->findOneBy(['trick' => $trick->getId(), 'firstImage' => true]);

        $commentForm = $this->formFactory->create(TrickCommentType::class)->handleRequest($request);

        $user = $this->security->getUser();
        if ($this->trickCommentTypeHandler->handleAddComment($commentForm, $trick) && null != $user) {
            $this->bag->add('success', 'Votre commentaire a été ajouté');

            return $redirect('trick_action', ['slug' => $trick->getSlug()]);
        }

        $comments = $this->commentRepository->findComments($trick->getId(), Comment::NUMBER_PER_PAGE, null);

        $maxPageNumberComments = ceil(
            $this->commentRepository->count(['trick' => $trick->getId()]) /
            Comment::NUMBER_PER_PAGE
        );

        $nextPage = $maxPageNumberComments > 1 ? true : false;

        return $responder(
            'trick/trick_page.html.twig',
            [
                    'form' => $commentForm->createView(),
                    'firstImage' => $firstImage,
                    'trick' => $trick,
                    'images' => $images,
                    'videos' => $videos,
                    'comments' => $comments,
                    'nextPage' => $nextPage,
                    'maxPageNumberComments' => $maxPageNumberComments,
                ]
        );
    }
}
