<?php


namespace App\Actions;

use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageTricksLoadedAction
 *
 * @Route("/tricks_loaded", name="tricks.loaded")
 */
final class HomepageTricksLoadedAction
{
    /** @var TrickRepositoryInterface */
    private $trickRepository;

    /**
     * HomepageTricksLoadedAction constructor.
     * @param TrickRepositoryInterface $trickRepository
     */
    public function __construct(TrickRepositoryInterface $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }


    public function __invoke(Request $request, ViewResponderInterface $responder)
    {
        $pageId = $request->query->get('page');
        $tricksNumber = $this->trickRepository->count([]);

        //check if there's a next page
        $nextPage =
            $pageId * Trick::NUMBER_OF_TRICKS_IN_HOMEPAGE < $tricksNumber ?
            true : false
        ;

        $offset = $pageId * Trick::NUMBER_OF_TRICKS_IN_HOMEPAGE - Trick::NUMBER_OF_TRICKS_IN_HOMEPAGE ;

        /** @var Trick $tricksToShow */
        $tricksToLoad = $this->trickRepository->findLatestWithFirstImageActive(Trick::NUMBER_OF_TRICKS_IN_HOMEPAGE, $offset);
        $page = $pageId++;

        return $responder (
            'include/_ajax_load_tricks.html.twig',
            [
                'tricksToLoad' => $tricksToLoad,
                'nextPage' => $nextPage,
                'numberOfThePage' => $page
            ]
        );
    }

}