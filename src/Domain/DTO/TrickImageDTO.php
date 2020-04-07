<?php


namespace App\Domain\DTO;


use App\Domain\Entity\TrickImage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickImageDTO
{
    protected $id;

    /** @var string */
    protected $alt;

    protected $image;

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
     * @param string $alt
     * @param $image
     */
    public function __construct($id = null, string $alt = '', $image = '', $firstImage = false)
    {
        $this->id = $id;
        $this->alt = $alt;
        $this->image = $image;
        $this->firstimage = $firstImage;
    }

    /**
     *
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param UploadedFile $image
     */
    public function setImage(string $image): UploadedFile
    {
        $this->image = $image;
    }

    /**
     * @param TrickImage $image
     */
    public static function createFromEntity(TrickImage $image, $i)
    {
        $dto = new self();

        $dto->setId($i);
        $dto->setAlt($image->getAltImage());
        $dto->setImage($image->getImageFileName());
        $dto->setFirstimage($image->getFirstImage());

        return $dto;
    }

    /**
     * @return string
     */
    public function getAlt(): string
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