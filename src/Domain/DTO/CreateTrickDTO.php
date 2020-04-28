<?php


namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\TrickDTOInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Form\CustomConstraints as AcmeAssert;

class CreateTrickDTO implements TrickDTOInterface
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="5", max="100")
     * @AcmeAssert\UniqueTrickTitle()
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
     */
    protected $groups;

    /**
     * @Assert\NotNull()
     */
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
    public function __construct(?string $title = '', ?string $content = '', ?string $groups = null, $imageslinks = [], $videoslinks = [])
    {
        $this->title = $title;
        $this->content = $content;
        $this->groups = $groups;
        $this->imageslinks = $imageslinks;
        $this->videoslinks = $videoslinks;
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
    public function setImageslinks($images): void
    {
        $this->imageslinks = $images;
    }

    /**
     * @param mixed $videos
     */
    public function setVideoslinks($videos): void
    {
        $this->videoslinks = $videos;
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
    public function getImageslinks()
    {
        $checkNewUploads = [];
        //Among new image uploads, only select those with upload files
        foreach ($this->imageslinks as $image) {
            if (!is_null($image->getImage())) {
                $checkNewUploads[] = $image;
            }
        }
        return $checkNewUploads;
    }

    /**
     *
     */
    public function getVideoslinks()
    {
        return $this->videoslinks;
    }
}