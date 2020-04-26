<?php


namespace App\Domain\DTO;


use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
        $this->imageslinks = $images;
        $this->videoslinks = $videos;
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
    public static function createFromEntity(Trick $trick, $files): self
    {
        $dto = new UpdateTrickDTO();
        $dto->setTitle($trick->getTitle());
        $dto->setContent($trick->getContent());

        $dto->setGroups($trick->getGroupTrick()->getName());
        $images = [];
        $i = 1;
        foreach ($trick->getTrickImages() as $image) {
            $images[] = TrickImageDTO::createFromEntity($image, $i, $files);
            $i++;
        }
        //$images returns an array of TrickImageDTO instances
        $dto->setImageslinks($images);

        $y = 1;
        $videos = [];
        foreach ($trick->getTrickVideos() as $video) {
            $videos[] = TrickVideoDTO::createFromEntity($video, $y);
            $y++;
        }
        $dto->setVideoslinks($videos);

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