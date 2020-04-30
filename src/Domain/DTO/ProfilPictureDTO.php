<?php

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\ProfilPictureDTOInterface;
use App\Domain\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class ProfilPictureDTO implements ProfilPictureDTOInterface
{
    /** @var string */
    protected $alt;

    /**
     * @Assert\Image(
     *     minWidth = 100,
     *     maxWidth = 1500,
     *     minHeight = 100,
     *     maxHeight = 1500
     * )
     */
    protected $profilPicture;

    /**
     * ProfilPictureDTO constructor.
     *
     * @param $profilPicture
     */
    public function __construct($profilPicture = null)
    {
        $this->profilPicture = $profilPicture;
        $this->alt = 'photo de profil';
    }

    public function getProfilPicture()
    {
        return $this->profilPicture;
    }

    /**
     * @param UploadedFile $image
     */
    public function setProfilPicture(string $image): UploadedFile
    {
        $this->image = $image;
    }

    public static function createFromEntity(User $user)
    {
        $dto = new self();

        $dto->setProfilPicture($image->getImageFileName());

        return $dto;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    public function getId()
    {
        return $this->id;
    }
}
