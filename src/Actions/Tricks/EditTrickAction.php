<?php

namespace App\Actions\Tricks;

use App\Domain\DTO\UpdateTrickDTO;
use App\Domain\Entity\GroupTrick;
use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickImage;
use App\Domain\Entity\TrickVideo;
use App\Domain\Repository\CommentRepository;
use App\Domain\Repository\GroupTrickRepository;
use App\Domain\Repository\TrickImageRepository;
use App\Domain\Repository\TrickVideoRepository;
use App\Domain\Repository\TrickRepository;
use App\Form\Handler\AddTrickCommentTypeHandler;
use App\Form\Handler\Interfaces\EditTrickTypeHandlerInterface;
use App\Form\Type\UpdateTrickType;
use App\Responders\RedirectResponder;
use App\Responders\ViewResponder;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class EditTrickAction
 *
 * @Route("/trick/edit/{slug}", name="trick_edit", methods={"GET","POST"})
 * @IsGranted("ROLE_USER")
 */
class EditTrickAction
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

    /** @var AddTrickCommentTypeHandler */
    private $addTrickCommentTypeHandler;

    /** @var UploaderHelper */
    private $uploaderHelper;

    /** @var FlashBagInterface */
    private $bag;

    /**
     * EditTrickAction constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param TrickRepository $trickRepository
     * @param TrickImageRepository $trickImageRepository
     * @param TrickVideoRepository $trickVideoRepository
     * @param Security $security
     * @param CommentRepository $commentRepository
     * @param FormFactoryInterface $formFactory
     * @param GroupTrickRepository $groupTrickRepository
     * @param EditTrickTypeHandlerInterface $editTrickTypeHandler
     * @param AddTrickCommentTypeHandler $addTrickCommentTypeHandler
     * @param UploaderHelper $uploaderHelper
     * @param FlashBagInterface $bag
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, TrickRepository $trickRepository, TrickImageRepository $trickImageRepository, TrickVideoRepository $trickVideoRepository, Security $security, CommentRepository $commentRepository, FormFactoryInterface $formFactory, GroupTrickRepository $groupTrickRepository, EditTrickTypeHandlerInterface $editTrickTypeHandler, AddTrickCommentTypeHandler $addTrickCommentTypeHandler, UploaderHelper $uploaderHelper, FlashBagInterface $bag)
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
        $this->addTrickCommentTypeHandler = $addTrickCommentTypeHandler;
        $this->uploaderHelper = $uploaderHelper;
        $this->bag = $bag;
    }

    public function __invoke(Request $request, ViewResponder $responder, RedirectResponder $redirect)
    {
        // TRICK PAGE
        /** @var Trick $trick */
        $trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')]);

        if(is_null($trick)) {
            throw new EntityNotFoundException('Pas de Trick "%s"', $trick->getSlug());
        }

        /** @var TrickImage $image */
        $images = $this->trickImageRepository->findBy(['trick' => $trick->getId()]);
        $trick->setTrickImages($images);

        /** @var TrickVideo $video */
        $videos = $this->trickVideoRepository->findBy(['trick' => $trick->getId()]);
        $trick->setTrickVideos($videos);

        $firstImage = $this->trickImageRepository->findOneBy(['trick' => $trick->getId(), 'firstImage' => true]);

        // EDITION TRICK

        // 1. Récupération du Nom du Trick avec le group_id
        $groupEntity = $this->groupTrickRepository->findOneBy(['id' => $trick->getGroupTrick()]);
        if(is_null($groupEntity)) {
            $groupEntity = new GroupTrick();
            $trick->setGroupTrick($groupEntity);
        }

        // 2. HYDRATATION DU DTO PAR L'ENTITY TRICK
        $files = [];
        foreach ($images as $image) {
            $files[] = $this->uploaderHelper->createTrickPictureFile($image->getImageFileName());
        }
        $dto = UpdateTrickDTO::createFromEntity($trick, $files);

        // 3. POPULATE THE FORM WITH THE DTO
        $trickType = $this->formFactory->create(UpdateTrickType::class, $dto)->handleRequest($request);

        if ($this->editTrickTypeHandler->handle($trickType, $trick)) {
            $this->bag->add('success', 'Le Trick a été modifié avec succès');
            return $redirect('trick_action', ['slug' => $trick->getSlug()]);
        }

        return $responder (
            'trick/trick_page_edit.html.twig',
            [
                'EditTrickForm' => $trickType->createView(),
                'firstImage' => $firstImage,
                'trick' => $trick,
                'images' => $images,
                'videos' => $videos,
            ]
        );
    }
}