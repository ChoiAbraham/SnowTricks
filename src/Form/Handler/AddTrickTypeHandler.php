<?php

namespace App\Form\Handler;

use App\Domain\Builder\Interfaces\TrickBuilderInterface;
use App\Domain\DTO\CreateTrickDTO;
use App\Domain\Entity\Trick;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Form\Handler\Interfaces\AddTrickTypeHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /** @var UrlGeneratorInterface */
    protected $urlGenerator;

    /** @var FlashBagInterface */
    protected $bag;

    /** @var bool */
    protected $checkImage;

    /**
     * AddTrickTypeHandler constructor.
     *
     * @param string $noImages
     */
    public function __construct(TrickBuilderInterface $trickBuilder, TrickRepositoryInterface $trickRepository, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, FlashBagInterface $bag, bool $checkImage = false)
    {
        $this->trickBuilder = $trickBuilder;
        $this->trickRepository = $trickRepository;
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
        $this->bag = $bag;
        $this->checkImage = $checkImage;
    }

    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var CreateTrickDTO $data */
            $data = $form->getData();

            /** @var Trick $trick */
            $trick = $this->trickBuilder->create($data)->getTrick(); // = new Trick();

            $this->em->persist($trick);
            $this->em->flush();

            // if no first image among all images, then redirect to account
            foreach ($trick->getTrickImages() as $image) {
                if (false == $image->getFirstImage()) {
                    $this->checkImage = true;

                    return false;
                }
            }

            // if no images, redirect to account
            if ($trick->getTrickImages()->isEmpty()) {
                $this->checkImage = true;

                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkImage(): bool
    {
        return $this->checkImage;
    }
}
