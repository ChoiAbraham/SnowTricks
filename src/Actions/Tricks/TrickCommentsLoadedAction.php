<?php


namespace App\Actions\Tricks;

use App\Domain\Entity\Comment;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\TrickRepository;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageTricksLoadedAction
 *
 * @Route("/commentsloaded/{slug}", name="comments.loaded")
 */
final class TrickCommentsLoadedAction
{
    /** @var CommentRepository */
    private $commentRepository;

    /** @var TrickRepository */
    private $trickRepository;

    /**
     * TrickCommentsLoadedAction constructor.
     * @param CommentRepository $commentRepository
     * @param TrickRepository $trickRepository
     */
    public function __construct(CommentRepository $commentRepository, TrickRepository $trickRepository)
    {
        $this->commentRepository = $commentRepository;
        $this->trickRepository = $trickRepository;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder)
    {
        $pageId = $request->query->get('page');
        $commentsNumber = $this->commentRepository->count([]);

        //check if there's a next page
        $nextPage =
            $pageId * Comment::NUMBER_PER_PAGE < $commentsNumber ?
                true : false
        ;

        $offset = $pageId * Comment::NUMBER_PER_PAGE - Comment::NUMBER_PER_PAGE ;

        $trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        /** @var Comment $commentsToShow */
        $commentsToLoad = $this->commentRepository->findComments($trick->getId(), Comment::NUMBER_PER_PAGE, $offset);
        $page = $pageId++;

        return $responder (
            'include/_ajax_load_more_comments_on_trick_page.html.twig',
            [
                'commentsToLoad' => $commentsToLoad,
                'nextPage' => $nextPage,
                'numberOfThePage' => $page
            ]
        );
    }
}