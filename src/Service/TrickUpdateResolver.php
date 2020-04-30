<?php

namespace App\Service;

use App\Domain\DTO\UpdateTrickDTO;
use App\Domain\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Security;

class TrickUpdateResolver
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /** @var UploaderHelper */
    private $uploaderHelper;

    /** @var Security */
    private $security;

    /**
     * TrickUpdateResolver constructor.
     */
    public function __construct(Filesystem $filesystem, EntityManagerInterface $manager, UploaderHelper $uploaderHelper, Security $security)
    {
        $this->filesystem = $filesystem;
        $this->manager = $manager;
        $this->uploaderHelper = $uploaderHelper;
        $this->security = $security;
    }

    public function updateTrickFromDTO(UpdateTrickDTO $updateTrickDTO, $trickEntity)
    {
        $user = $this->security->getUser();
        /* @var Trick $trickEntity */
        $trickEntity->setTitle($updateTrickDTO->getTitle());
        $trickEntity->setContent($updateTrickDTO->getContent());
        $trickEntity->setGroupTrick($updateTrickDTO->getGroups());
        $trickEntity->setUpdatedAt(new \DateTime('now'));
        $trickEntity->setLastUser($user);

        // get Images that have an Id (with or without uploadFiles)
        $trickImagesDTOs = $updateTrickDTO->getImageslinksWithIds();

        $i = 0; // Incrementation
        $imageId = 1; // trickImageId starts at 1
        // TrickImages > TrickImagesDTOs so i loop over TrickImages
        foreach ($trickEntity->getTrickImages() as $trickImage) {
            // Check imageId exists
            if (isset($trickImagesDTOs[$i]) && $trickImagesDTOs[$i]->getId() == $imageId) {
                // Then the image has not been deleted -> update
                /** @var UploadedFile $uploadedFile */
                $uploadedFile = $trickImagesDTOs[$i]->getImage();

                // if a New File has been Uploaded
                if ($uploadedFile) {
                    // Remove the old picture
                    $imageFileName = $trickImage->getImageFileName();
                    $this->uploaderHelper->deleteTrickImage($imageFileName);

                    // Upload the New File
                    $filename = ImageFileNameService::generateFileName($uploadedFile);
                    $this->uploaderHelper->uploadTrickImage($uploadedFile, $filename);

                    $trickImage->setImageFileName($filename);
                }

                // RESET VALUES FOR EACH IMAGES (Alt, firstImage)
                $trickImage->setFirstImage($trickImagesDTOs[$i]->getFirstImage());
                $trickImage->setAltImage($trickImagesDTOs[$i]->getAlt());
            } else {
                $this->manager->remove($trickImage);
            }

            ++$imageId;
            ++$i;
        }

        $y = 0; // Incrementation
        $videoId = 1; // trickVideoId starts at 1
        // get Videos that have an Id
        $trickVideoDTOs = $updateTrickDTO->getVideolinksWithIds();
        foreach ($trickEntity->getTrickVideos() as $trickVideo) {
            if ($trickVideoDTOs[$y]->getId() == $videoId) {
                $trickVideo->setPathUrl($trickVideoDTOs[$y]);
            } else {
                $this->manager->remove($trickVideo);
            }
            ++$videoId;
            ++$y;
        }

        $this->manager->flush();

        return $trickEntity;
    }
}
