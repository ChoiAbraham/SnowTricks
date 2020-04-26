<?php


namespace App\Domain\DTO;


use App\Domain\Entity\TrickImage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class TrickImageDTO
{
    protected $id;

    /** @var string */
    protected $alt;

    /**
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 500,
     *     minHeight = 200,
     *     maxHeight = 500
     * )
     */
    protected $image;

    /**
     * @Assert\Unique
     */
    protected $firstimage;

    /**
     * @return mixed
     */
    public function getFirstimage()
    {
        return $this->firstimage;
    }

    /**
     * @param mixed $firstimage
     */
    public function setFirstimage($firstimage): void
    {
        $this->firstimage = $firstimage;
    }

    /**
     * TrickImageDTO constructor.
     * @param $id
     * @param $alt
     */
    public function __construct($id = null, ?string $alt = '', $imageFileName = '', $firstImage = false)
    {
        $this->id = $id;
        $this->alt = $alt;
        $this->image = $imageFileName;
        $this->firstimage = $firstImage;
    }

    /**
     * @param TrickImage $image
     */
    public static function createFromEntity(TrickImage $image, $i, $files)
    {
        $dto = new self();
        $dto->setId($i);
        $dto->setAlt($image->getAltImage());
        $dto->setImage($files[$i-1]);
        $dto->setFirstimage($image->getFirstImage());

        return $dto;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt(string $alt): void
    {
        $this->alt = $alt;
    }

    /**
     * @return File
     */
    public function getImage(): File
    {
        return $this->image;
    }

    /**
     * @param File $imageFileName
     */
    public function setImage(File $imageFileName): void
    {
        $this->image = $imageFileName;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}