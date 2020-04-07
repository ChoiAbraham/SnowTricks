<?php


namespace App\Domain\DTO;


use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateTrickDTO implements TrickDTOInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="100")
     */
    protected $title;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $content;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $groups;

    protected $imageslinks;

    protected $videoslinks;

    /**
     * TrickDTO constructor.
     * @param string|null $title
     * @param string|null $content
     * @param string|null $groups
     * @param ArrayCollection $image
     * @param ArrayCollection $video
     */
    public function __construct(?string $title = '', ?string $content = '', ?string $groups = '', $images = [], $videos = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->groups = $groups;
        $this->images = $images;
        $this->videos = $videos;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param string|null $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     *
     */
    public function setGroups($groups): void
    {
        $this->groups = $groups;
    }

    /**
     * Prepopulate the data from the Trick Entity
     *
     */
    public static function createFromEntity(Trick $trick): self
    {
        $dto = new UpdateTrickDTO();
        $dto->setTitle($trick->getTitle());
        $dto->setContent($trick->getContent());

        // QUESTION
        // je set le group au nom et je retire le groupTrick instance, correct?
        $dto->setGroups($trick->getGroupTrick()->getName());
        $images = [];
        //dd($trick->getTrickImages());
        $i = 1;
        foreach ($trick->getTrickImages() as $image) {
            $images[] = TrickImageDTO::createFromEntity($image, $i);
            $i++;
        }
        //$images returns an array of TrickImageDTO instances
        $dto->setImageslinks($images);

        $videos = [];
        foreach ($trick->getTrickVideos() as $video) {
            $videos[] = TrickVideoDTO::createFromEntity($video);
        }
        $dto->setVideoslinks($videos);

        //dd($dto);
        return $dto;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @return string|null
     */
    public function getGroups(): ?string
    {
        return $this->groups;
    }

    /**
     * @return mixed
     */
    public function getImageslinks()
    {
        return $this->imageslinks;
    }

    /**
     * @param mixed $imageslinks
     */
    public function setImageslinks($imageslinks): void
    {
        $this->imageslinks = $imageslinks;
    }

    /**
     * @return mixed
     */
    public function getVideoslinks()
    {
        return $this->videoslinks;
    }

    /**
     * @param mixed $videoslinks
     */
    public function setVideoslinks($videoslinks): void
    {
        $this->videoslinks = $videoslinks;
    }
}