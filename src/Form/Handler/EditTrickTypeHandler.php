<?php


namespace App\Form\Handler;


use App\Domain\Builder\Interfaces\TrickBuilderInterface;
use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickImage;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Form\Handler\Interfaces\EditTrickTypeHandlerInterface;
use Symfony\Component\Form\FormInterface;

class EditTrickTypeHandler implements EditTrickTypeHandlerInterface
{
    /**
     * @var TrickBuilderInterface
     */
    private $trickBuilder;

    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * AddTrickTypeHandler constructor.
     * @param TrickBuilderInterface $trickBuilder
     * @param TrickRepositoryInterface $trickRepository
     */
    public function __construct(TrickBuilderInterface $trickBuilder, TrickRepositoryInterface $trickRepository)
    {
        $this->trickBuilder = $trickBuilder;
        $this->trickRepository = $trickRepository;
    }

    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->getData()->getImageslinks());
            dd($form->getData());
            // CAS 1 : UPDATE
            $trick = Trick::updateFromDTO($form->getData());
            //dd($trick); //contient les $trickImages sans les nouvelles $images et vidéos
            // je flush $trick
            // $entityManager->flush();

            // CAS 2 : NOUVELLES IMAGES ET VIDEOS
            // pour les nouvelles images
            $allImages = $form->getData()->getImageslinks();
            foreach($allImages as $image) {
                if(is_null($image->getId())) {
                    $newImage = TrickImage::updateFromDTO($image);
                    dd($newImage);
                    // je persist et je flush TrickImage en passant l'id du trick (avec l'objet $request)
                }
            }

            // idem pour vidéo, je passe une propriété Id
            $allVideos = $form->getData()->getVideoslinks();
            foreach($allVideos as $video) {
                // TODO - sélectionner ceux qui ont des Id null
            }
            //dd($image);

            // $entityManager->persist($$image);
            // $entityManager->flush();

            return true;
        }

        return false;
    }

}