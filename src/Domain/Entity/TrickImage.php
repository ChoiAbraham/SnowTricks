<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TrickFile.
 *
 * @ORM\Table(name="trick_image_entity")
 * @ORM\Entity(repositoryClass="App\Domain\Repository\TrickImageRepository")
 */
class TrickImage
{
    /**
     * @var id
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    protected $imageFileName;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $firstImage = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $altImage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Entity\Trick", inversedBy="trickImages")
     * @ORM\JoinColumn(name="trick_id", referencedColumnName="id", nullable=false)
     */
    private $trick;

    /**
     * TrickImage constructor.
     */
    public function __construct(string $imageFileName, bool $firstImage = false, string $altImage = '')
    {
        $this->imageFileName = $imageFileName;
        $this->firstImage = $firstImage;
        $this->altImage = $altImage;
    }

    /**
     * @return bool
     */
    public function getFirstImage(): bool
    {
        return $this->firstImage;
    }

    public function setFirstImage(bool $firstImage = false): void
    {
        $this->firstImage = $firstImage;
    }

    /**
     * @return string
     */
    public function getAltImage(): string
    {
        return $this->altImage;
    }

    public function setAltImage(string $altImage): void
    {
        $this->altImage = $altImage;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImageFileName(): string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(string $imageFileName): void
    {
        $this->imageFileName = $imageFileName;
    }

    /**
     * Get Trick.
     *
     * @return Trick|null
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    /**
     * Set Trick.
     *
     * @return TrickImage
     */
    public function setTrick(?Trick $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
