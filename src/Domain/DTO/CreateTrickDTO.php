<?php


namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Trick;
use App\Service\ImageFileNameService;
use App\Service\UploaderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CreateTrickDTO implements TrickDTOInterface
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

    protected $images;

    protected $videos;

    /**
     * TrickDTO constructor.
     * @param string|null $title
     * @param string|null $content
     * @param string|null $groups
     * @param ArrayCollection $image
     * @param ArrayCollection $video
     */
    public function __construct(?string $title = '', ?string $content = '', ?string $groups='', $images = [], $videos = [])
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
     * @param mixed $images
     */
    public function setImages($images): void
    {
        $this->images = $images;
    }

    /**
     * @param mixed $videos
     */
    public function setVideos($videos): void
    {
        $this->videos = $videos;
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
     *
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     *
     */
    public function getVideos()
    {
        return $this->videos;
    }
}