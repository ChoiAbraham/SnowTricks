<?php

namespace App\Actions;

use App\Actions\Interfaces\EditTrickActionInterface;
use App\Domain\DTO\TrickDTO;
use App\Domain\DTO\TrickImageDTO;
use App\Domain\DTO\UpdateTrickDTO;
use App\Domain\Entity\Trick;
use App\Domain\Repository\GroupTrickRepository;
use App\Domain\Repository\TrickImageRepository;
use App\Domain\Repository\TrickRepository;
use App\Domain\Repository\TrickVideoRepository;
use App\Form\Handler\Interfaces\EditTrickTypeHandlerInterface;
use App\Form\Type\CreateTrickType;
use App\Form\Type\TrickType;
use App\Form\Type\UpdateTrickType;
use App\Responders\Interfaces\ViewResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class EditTrickAction
 *
 * @Route("/trick/edit/{id}", name="edit_trick", methods={"GET","POST"})
 */
final class EditTrickAction implements EditTrickActionInterface
{
    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TrickRepository */
    protected $trickRepository;

    /** @var TrickImageRepository */
    protected $trickImageRepository;

    /** @var TrickVideoRepository */
    protected $trickVideoRepository;

    /** @var GroupTrickRepository */
    protected $groupTrickRepository;

    /** @var EditTrickTypeHandlerInterface */
    private $editTrickTypeHandler;

    /**
     * EditTrickAction constructor.
     * @param FormFactoryInterface $formFactory
     * @param TrickRepository $trickRepository
     * @param TrickImageRepository $trickImageRepository
     * @param TrickVideoRepository $trickVideoRepository
     * @param GroupTrickRepository $groupTrickRepository
     * @param EditTrickTypeHandlerInterface $editTrickTypeHandler
     */
    public function __construct(FormFactoryInterface $formFactory, TrickRepository $trickRepository, TrickImageRepository $trickImageRepository, TrickVideoRepository $trickVideoRepository, GroupTrickRepository $groupTrickRepository, EditTrickTypeHandlerInterface $editTrickTypeHandler)
    {
        $this->formFactory = $formFactory;
        $this->trickRepository = $trickRepository;
        $this->trickImageRepository = $trickImageRepository;
        $this->trickVideoRepository = $trickVideoRepository;
        $this->groupTrickRepository = $groupTrickRepository;
        $this->editTrickTypeHandler = $editTrickTypeHandler;
    }

    public function __invoke(Request $request, ViewResponderInterface $responder)
    {
        // Edition :
        // HYDRATION DU DTO PAR L'ENTITE TRICK
        // ETAPE 1 : Récupération de l'entité

        // Récupération de l'entité Trick
        /** @var Trick $trickEntity */
        $trickEntity = $this->trickRepository->find($request->attributes->get('id'));

        // Récupération des Images du Trick
        $imagesEntity = $this->trickImageRepository->findBy(['trick' => $request->attributes->get('id')]);
        $trickEntity->setTrickImages($imagesEntity); //ArrayCollection

        // Récupération des Vidéos du Trick
        $videosEntity = $this->trickVideoRepository->findBy(['trick' => $request->attributes->get('id')]);
        $trickEntity->getTrickVideos($videosEntity); //ArrayCollection

        // Récupération du Nom du Trick avec le group_id
        $groupEntity = $this->groupTrickRepository->findOneBy(['id' => $trickEntity->getGroupTrick()]);
        $trickEntity->setGroupTrick($groupEntity);

        // ETAPE 2 : HYDRATATION DU DTO
        $dto = UpdateTrickDTO::createFromEntity($trickEntity);
        //dd($dto);

        // ETAPE 3 : POPULATE THE FORM WITH THE DTO
        $trickType = $this->formFactory->create(UpdateTrickType::class, $dto)->handleRequest($request);
        //dd($trickType);

        if ($this->editTrickTypeHandler->handle($trickType)) {
            // ... Redirect??
        }

        return $responder (
            'trick/trick_form.html.twig',
            [
                'form' => $trickType->createView()
            ],
            false
        );
    }
}
