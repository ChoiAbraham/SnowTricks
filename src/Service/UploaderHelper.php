<?php


namespace App\Service;

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
}