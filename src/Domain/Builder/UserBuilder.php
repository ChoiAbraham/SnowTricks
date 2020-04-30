<?php

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\DTO\CreateUserDTO;
use App\Domain\DTO\ProfilPictureDTO;
use App\Domain\Entity\User;
use App\Service\ImageFileNameService;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;

class UserBuilder implements UserBuilderInterface
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * UserBuilder constructor.
     */
    public function __construct(User $user, UploaderHelper $uploaderHelper, EntityManagerInterface $em)
    {
        $this->user = $user;
        $this->uploaderHelper = $uploaderHelper;
        $this->em = $em;
    }

    /**
     * @param
     *
     * @return UserBuilder
     */
    public function create(CreateUserDTO $dto): self
    {
        $this->user = new User(
            $dto->getUsername(),
            $dto->getEmail(),
            $dto->getPassword()
        );

        return $this;
    }

    public function updateProfilPicture(ProfilPictureDTO $dto, User $user): self
    {
        /** @var UploadedFile $uploadFile */
        $uploadFile = $dto->getProfilPicture();
        $newFileName = ImageFileNameService::generateFileName($uploadFile);

        if ($uploadFile) {
            $this->uploaderHelper->deleteProfilPictureImage($user->getPicturePath());
            $this->uploaderHelper->uploadProfilPictureImage($uploadFile, $newFileName);
        }

        $user->setPicturePath($newFileName);
        $this->em->flush();

        $this->user = $user;

        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
