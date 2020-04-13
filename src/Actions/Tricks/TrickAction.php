<?php


namespace App\Actions\Tricks;

use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Event\UserCreatedEvent;
use App\Domain\Repository\TrickRepository;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickDisplay
 *
 * @Route("/trick/{slug}/{id}", name="trick_action")
 */
class TrickAction
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    /** @var trickRepository */
    protected $trickRepository;

    /**
     * TrickAction constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param TrickRepository $trickRepository
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, TrickRepository $trickRepository, Trick $trick)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->trickRepository = $trickRepository;
    }

    public function __invoke(string $slug, ViewResponder $responder, RedirectResponder $redirect)
    {
        $trickRepository = $this->trickRepository->findOneBy(['slug' => $slug]);
        //or $trick = $this->trickRepository->find($id);

        if(!$trickRepository)
        {
            return $redirect('homepage_action');
        }
        //Handling 404's
        /*
        if (!$article) {
            //throw createNotFoundException(sprintf('No Trick for slug "%s"', $slug));
        }
        */
        //$user = new User();
        //$this->eventDispatcher->dispatch(UserCreatedEvent::NAME, new UserCreatedEvent($user));

        return $responder (
            'trick/trick_display.html.twig',
            [
                'slug' => $slug,
                'trick' => $trickRepository
            ]
        );
    }
}