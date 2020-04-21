<?php


namespace App\Actions\Tricks;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickImage;
use App\Domain\Entity\TrickVideo;
use App\Domain\Entity\User;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickImageRepository;
use App\Domain\Repository\TrickVideoRepository;
use App\Domain\Repository\TrickRepository;
use App\Form\Handler\AddTrickCommentTypeHandler;
use App\Form\Type\addTrickCommentType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use App\Service\VideoLinkHelper;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class TrickDisplay
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

    /** @var AddTrickCommentTypeHandler */
    private $addTrickCommentTypeHandler;

    /** @var VideoLinkHelper */
    private $videoHelper;

    /**
     * TrickAction constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param TrickRepository $trickRepository
     * @param TrickImageRepository $trickImageRepository
     * @param TrickVideoRepository $trickVideoRepository
     * @param Security $security
     * @param CommentRepository $commentRepository
     * @param FormFactoryInterface $formFactory
     * @param AddTrickCommentTypeHandler $addTrickCommentTypeHandler
     * @param VideoLinkHelper $videoHelper
     */
    public function __construct(VideoLinkHelper $videoHelper, EventDispatcherInterface $eventDispatcher, TrickRepository $trickRepository, TrickImageRepository $trickImageRepository, TrickVideoRepository $trickVideoRepository, Security $security, CommentRepository $commentRepository, FormFactoryInterface $formFactory, AddTrickCommentTypeHandler $addTrickCommentTypeHandler)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->trickRepository = $trickRepository;
        $this->trickImageRepository = $trickImageRepository;
        $this->trickVideoRepository = $trickVideoRepository;
        $this->security = $security;
        $this->commentRepository = $commentRepository;
        $this->formFactory = $formFactory;
        $this->addTrickCommentTypeHandler = $addTrickCommentTypeHandler;
        $this->videoHelper = $videoHelper;
    }

    public function __invoke(Request $request, ViewResponder $responder, RedirectResponder $redirect)
    {
        //tester dailymotion
        // 1. link
        $link = '
        
        ';
        $result = $this->videoHelper->transformLinkForEmbedIframe($link);
        dd($result);
        // 2. Iframe

        // 3. link Iframe

        //tester vimeo
        // 1. link

        // 2. Iframe

        //tester youtube
        // 1. link

        // 2. Iframe

        /** @var Trick $trick */
        $trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        if(is_null($trick)) {
            throw new EntityNotFoundException('Pas de Trick "%s"', $trick->getSlug());
        }

        /** @var TrickImage $image */
        $images = $this->trickImageRepository->findBy(['trick' => $trick->getId()]);
        /** @var TrickVideo $video */
        $videos = $this->trickVideoRepository->findBy(['trick' => $trick->getId()]);

        $firstImage = $this->trickImageRepository->findOneBy(['trick' => $trick->getId(), 'firstImage' => true]);

        $commentForm = $this->formFactory->create(addTrickCommentType::class)->handleRequest($request);

        $user = $this->security->getUser();
        if ($this->addTrickCommentTypeHandler->handle($commentForm) && $user != null) {
            return $redirect('trick', ['slug' => $trick->getSlug()]);
        }

        /** @var Comment $comments */
        $comments = $this->commentRepository->findBy(['trick' => $trick->getId()], [], Comment::NUMBER_PER_PAGE, null);

        $maxPageNumberComments = ceil(
            $this->commentRepository->count(['trick' => $trick->getId()]) /
            Comment::NUMBER_PER_PAGE
        );

        $nextPage = $maxPageNumberComments > 1 ? true : false;

        return $responder (
        'trick/trick_page.html.twig',
                [
                    'form' => $commentForm->createView(),
                    'firstImage' => $firstImage,
                    'trick' => $trick,
                    'images' => $images,
                    'videos' => $videos,
                    'comments' => $comments,
                    'nextPage' => $nextPage,
                    'maxPageNumberComments' => $maxPageNumberComments
                ]
        );
    }
}