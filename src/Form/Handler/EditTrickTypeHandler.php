<?php

namespace App\Form\Handler;

use App\Domain\Builder\Interfaces\TrickBuilderInterface;
use App\Domain\Builder\Interfaces\TrickImageBuilderInterface;
use App\Domain\Builder\Interfaces\TrickVideoBuilderInterface;
use App\Domain\DTO\UpdateTrickDTO;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Form\Handler\Interfaces\EditTrickTypeHandlerInterface;
use App\Service\TrickUpdateResolver;
use Doctrine\ORM\EntityManagerInterface;
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

    /** @var EntityManagerInterface */
    private $em;

    /** @var TrickUpdateResolver */
    private $trickUpdateResolver;

    /** @var TrickImageBuilderInterface */
    private $trickImageBuilder;

    /** @var TrickVideoBuilderInterface */
    private $trickVideoBuilder;

    /**
     * EditTrickTypeHandler constructor.
     */
    public function __construct(TrickBuilderInterface $trickBuilder, TrickRepositoryInterface $trickRepository, EntityManagerInterface $em, TrickUpdateResolver $trickUpdateResolver, TrickImageBuilderInterface $trickImageBuilder, TrickVideoBuilderInterface $trickVideoBuilder)
    {
        $this->trickBuilder = $trickBuilder;
        $this->trickRepository = $trickRepository;
        $this->em = $em;
        $this->trickUpdateResolver = $trickUpdateResolver;
        $this->trickImageBuilder = $trickImageBuilder;
        $this->trickVideoBuilder = $trickVideoBuilder;
    }

    public function handle(FormInterface $form, $trick): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UpdateTrickDTO $data */
            $data = $form->getData();

            // Step 1 : Update Trick (Title/Content/Group/Images/Videos) // flush
            $trick = $this->trickUpdateResolver->updateTrickFromDTO($form->getData(), $trick);

            // Step 2 : Update Trick (New Images / New Videos) // persist and flush
            // TrickImageBuilder // persist and flush
            $imageDTOs = $data->getImageslinksWithNoIds();
            $this->trickBuilder->setTrickImages($trick, $imageDTOs);

            $videoDTOs = $data->getVideolinksWithNoIds();
            $this->trickBuilder->setTrickVideos($trick, $videoDTOs);

            $this->em->persist($trick);
            $this->em->flush();

            return true;
        }

        return false;
    }
}
