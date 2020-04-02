<?php


namespace App\Form\Handler;


use App\Domain\Builder\Interfaces\TrickBuilderInterface;
use App\Domain\Builder\Interfaces\TrickVideoBuilderInterface;
use App\Domain\DTO\CreateTrickDTO;
use App\Domain\DTO\TrickVideoDTO;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Form\Handler\Interfaces\AddTrickTypeHandlerInterface;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddTrickTypeHandler implements AddTrickTypeHandlerInterface
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

    /**
     * AddTrickTypeHandler constructor.
     * @param TrickBuilderInterface $trickBuilder
     * @param TrickRepositoryInterface $trickRepository
     * @param EntityManagerInterface $em
     */
    public function __construct(TrickBuilderInterface $trickBuilder, TrickRepositoryInterface $trickRepository, EntityManagerInterface $em)
    {
        $this->trickBuilder = $trickBuilder;
        $this->trickRepository = $trickRepository;
        $this->em = $em;
    }

    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateTrickDTO $data */
            $data = $form->getData();
            // si je n'utilise pas les datamapper je ne vois pas le uploadFile

            /** @var Trick $trick */
            $trick = $this->trickBuilder->create($form->getData())->getTrick(); // = new Trick();

            $this->em->persist($trick);
            $this->em->flush();

            return true;
        }

        return false;
    }
}