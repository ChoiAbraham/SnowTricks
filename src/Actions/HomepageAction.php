<?php

namespace App\Actions;

use App\Actions\Interfaces\HomeActionInterface;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Domain\Repository\TrickRepository;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomepageAction.
 *
 * @Route(
 *     "/",
 *     name="homepage_action",
 * )
 */
final class HomepageAction implements HomeActionInterface
{
    /** @var TrickRepository */
    private $trickRepository;

    /**
     * HomepageAction constructor.
     */
    public function __construct(TrickRepositoryInterface $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    public function __invoke(ViewResponderInterface $responder)
    {
        /** @var Trick $tricks */
        $tricks = $this->trickRepository->findLatestWithFirstImageActive(Trick::NUMBER_OF_TRICKS_IN_HOMEPAGE, null);

        /** @var $maxPageNumber */
        $maxPageNumber = ceil(
            $this->trickRepository->count([]) / Trick::NUMBER_OF_TRICKS_IN_HOMEPAGE
        );
        $nextPage = $maxPageNumber > 1 ? true : false;

        return $responder(
            'core/home.html.twig',
            [
                'tricks' => $tricks,
                'maxPageNumber' => $maxPageNumber,
                'nextPage' => $nextPage,
            ]
        );
    }
}
