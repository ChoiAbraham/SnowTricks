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
use Symfony\Component\Security\Core\Security;

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

    /** @var Security */
    private $security;

    /**
     * TrickBuilder constructor.
     * @param Trick $trick
     * @param TrickImageBuilderInterface $trickImageBuilder
     * @param TrickVideoBuilderInterface $trickVideoBuilder
     * @param GroupTrickRepository $groupTrickRepository
     * @param UserRepository $userRepository
     * @param array $trickImages
     * @param Security $security
     */
    public function __construct(Trick $trick, TrickImageBuilderInterface $trickImageBuilder, TrickVideoBuilderInterface $trickVideoBuilder, GroupTrickRepository $groupTrickRepository, UserRepository $userRepository, Security $security)
    {
        $this->trick = $trick;
        $this->trickImageBuilder = $trickImageBuilder;
        $this->trickVideoBuilder = $trickVideoBuilder;
        $this->groupTrickRepository = $groupTrickRepository;
        $this->userRepository = $userRepository;
        $this->security = $security;
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
            $this->getUser()
        );

        //TrickVideo Instances from TrickVideoDTOs
        $trickVideos = $this->getTrickVideos($dto->getVideoslinks());
        foreach ($trickVideos as $trickVideo) {
            //set the trick to the Video and add the video to the Trick
            $this->trick->videos($trickVideo);
        }

        $trickImages = $this->getTrickImages($dto->getImageslinks());
        foreach ($trickImages as $trickImage) {
            //set the trick to the Image and add the Image to the Trick
            $this->trick->images($trickImage);
        }

        return $this;
    }

    public function getUser()
    {
        $user =  $this->security->getUser();
        return $user;
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

    public function getGroupTrick($groupTrick)
    {
        if($groupTrick == null) {
            return null;
        }
        $name = Trick::LIST_GROUPS[$groupTrick];
        $result = $this->groupTrickRepository->findOneBy(array('name' => $name));

        return $result;
    }

    public function getTrickImages($imageDTOs)
    {
        $trickImages = [];
        foreach ($imageDTOs as $imageDTO) {
            /* @var TrickImageDTO $imageDTO */
            $image = $this->trickImageBuilder->create($imageDTO)->getTrickImage(); // = new TrickImage
            $trickImages[] = $image;
        }

        return $trickImages;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}