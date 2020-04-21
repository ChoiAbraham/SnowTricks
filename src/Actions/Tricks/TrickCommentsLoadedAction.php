<?php


namespace App\Actions\Tricks;

use App\Domain\Entity\Comment;
use App\Domain\Repository\CommentRepository;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageTricksLoadedAction
 *
 * @Route("/commentsloaded", name="comments.loaded")
 */
final class TrickCommentsLoadedAction
{
    /** @var CommentRepository */
    private $commentRepository;

    /**
     * TrickCommentsLoadedAction constructor.
     * @param CommentRepository $commentRepository
     */
    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
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

        /** @var Comment $commentsToShow */
        $commentsToLoad = $this->commentRepository->findBy([], [], Comment::NUMBER_PER_PAGE, $offset);
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