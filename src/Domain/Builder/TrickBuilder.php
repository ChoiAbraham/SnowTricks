<?php


namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\TrickBuilderInterface;
use App\Domain\Builder\Interfaces\TrickImageBuilderInterface;
use App\Domain\Builder\Interfaces\TrickVideoBuilderInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\TrickImageDTO;
use App\Domain\DTO\TrickVideoDTO;
use App\Domain\Entity\Trick;
use App\Domain\Repository\GroupTrickRepository;
use App\Domain\Repository\UserRepository;

class TrickBuilder implements TrickBuilderInterface
{
    /**
     * @var Trick
     */
    private $trick;

    /**
     * @var TrickImageBuilderInterface
     */
    private $trickImageBuilder;

    /**
     * @var TrickVideoBuilderInterface
     */
    private $trickVideoBuilder;

    /**
     * @var GroupTrickRepository
     */
    private $groupTrickRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    private $trickImages = [];

    /**
     * TrickBuilder constructor.
     * @param TrickImageBuilderInterface $trickImageBuilder
     * @param TrickVideoBuilderInterface $trickVideoBuilder
     * @param GroupTrickRepository $groupTrickRepository
     * @param UserRepository $userRepository
     */
    public function __construct(TrickImageBuilderInterface $trickImageBuilder, TrickVideoBuilderInterface $trickVideoBuilder, GroupTrickRepository $groupTrickRepository, UserRepository $userRepository)
    {
        $this->trickImageBuilder = $trickImageBuilder;
        $this->groupTrickRepository = $groupTrickRepository;
        $this->userRepository = $userRepository;
        $this->trickVideoBuilder = $trickVideoBuilder;
    }

    /**
     * @param string $content
     * @return TrickBuilder
     */
    public function create(TrickDTOInterface $dto): self
    {
        $this->trick = new Trick(
            $dto->getTitle(),
            $dto->getContent(),
            $this->getGroupTrick($dto->getGroups()),
            $this->getUserTest()
        );

        //TrickVideo Instances from TrickVideoDTOs
        $trickVideos = $this->getTrickVideos($dto->getVideos());
        foreach ($trickVideos as $trickVideo) {
            //set the trick to the Video and add the video to the Trick
            $this->trick->videos($trickVideo);
        }

        $trickImages = $this->getTrickImages($dto->getImages());
        foreach ($trickImages as $trickImage) {
            //set the trick to the Image and add the Image to the Trick
            $this->trick->images($trickImage);
        }

        return $this;
    }

    public function getUserTest()
    {
        $userRandom = $this->userRepository->find(190);
        return $userRandom;
    }

    public function getTrickVideos($videoDTOs):array
    {
        $trickVideos = [];
        foreach ($videoDTOs as $videoDTO) {
            /* @var TrickVideoDTO $videoDTO*/
            $video = $this->trickVideoBuilder->create($videoDTO)->getTrickVideo(); // = new TrickVideo
            $trickVideos[] = $video;
        }

        return $trickVideos;
    }

    public function getGroupTrick(int $groupTrick)
    {
        $name = Trick::LIST_GROUPS[$groupTrick];
        $result = $this->groupTrickRepository->findOneBy(array('name' => $name));
        $id = $result->getId();

        return $result;
    }

    public function getTrickImages($imageDTOs)
    {
        foreach ($imageDTOs as $imageDTO) {
            /* @var TrickImageDTO $imageDTO */
            $image = $this->trickImageBuilder->create($imageDTO)->getTrickImage(); // = new TrickImage
            $this->trickImages[] = $image;
        }

        return $this->trickImages;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}