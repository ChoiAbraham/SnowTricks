<?php


namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    /**
     * @var string
     */
    private $uploadPath;

    /**
     * UploaderHelper constructor.
     * @param string $uploadPath
     * @param Filesystem $filesystem
     */
    public function __construct(string $uploadPath)
    {
        $this->uploadPath = $uploadPath;
    }

    public function uploadTrickImage(UploadedFile $uploadedFile, $newFileName)
    {
        $destination = $this->uploadPath.'/trick_picture';

        //$uploadedFile->move($destination, $nameToGiveToTheFile);
        // the easiest name to use is : $uploadedFile->getClientOriginalName();
        // careful : security (have to be an image, not .php extension)
        // careful : has to be unique => newFileName
        try {
            $uploadedFile->move(
                $destination,
                $newFileName
            );
        } catch (FileException $e) {
            //
        }
    }

    public function uploadProfilPictureImage(UploadedFile $uploadedFile, $newFileName)
    {
        $destination = $this->uploadPath.'/profil_picture';

        try {
            $uploadedFile->move(
                $destination,
                $newFileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }

    public function deleteProfilPictureImage(?string $filename)
    {
        $fileSystem = new FileSystem();
        $destination = $this->uploadPath.'/profil_picture/'. $filename;
        $fileSystem->remove($destination);
    }

    public function createTrickPictureFile(?string $filename)
    {
        $file = new File($this->uploadPath . '/trick_picture/' . $filename);
        return $file;
    }
}