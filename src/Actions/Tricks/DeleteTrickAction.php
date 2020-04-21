<?php

namespace App\Actions\Tricks;

use App\Actions\Interfaces\DeleteTrickActionInterface;
use App\Domain\Entity\Trick;
use App\Domain\Repository\TrickRepository;
use App\Responders\Interfaces\ViewResponderInterface;
use App\Responders\RedirectResponder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Class DeleteTrickAction
 *
 * @Route("/delete/{slug}", name="edit_trick_delete")
 */
final class DeleteTrickAction extends AbstractController implements DeleteTrickActionInterface
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * @var TokenStorageInterface *
     */
    private $tokenStorage;

    /** @var TrickRepository */
    private $trickRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrf;

    /**
     * DeleteTrickAction constructor.
     * @param EntityManagerInterface $manager
     * @param TokenStorageInterface $tokenStorage
     * @param TrickRepository $trickRepository
     * @param FlashBagInterface $flashBag
     */
    public function __construct(EntityManagerInterface $manager, TokenStorageInterface $tokenStorage, TrickRepository $trickRepository, FlashBagInterface $flashBag, CsrfTokenManagerInterface $csrf)
    {
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
        $this->trickRepository = $trickRepository;
        $this->flashBag = $flashBag;
        $this->csrf = $csrf;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder, RedirectResponder $redirect)
    {
        dd('yes it worked');

        $trickEntity = $this->trickRepository->find($request->attributes->get('slug'));
        //dd($this->tokenStorage->getToken()->getUser());

        if ($this->csrf->isTokenValid(new CsrfToken('delete' . $trickEntity->getId(), $request->get('_token')))) {
            $this->flashBag->add('success', 'it worked');

            // TODO - images et videos à supprimer
            // TODO - modal
            //$this->manager->remove($trickEntity);
            //$this->manager->flush();
            return new Response('suppression test');
        }
        /** @var Trick $trickEntity */
        return $redirect('homepage_action');
    }
}
