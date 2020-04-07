<?php


namespace App\Service;


use App\Domain\Entity\TrickImage;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageFileNameService
{
    public static function generateFileName(UploadedFile $uploadedFile)
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        // Urlizer from the gedmo/doctrine-extensions // Urlizer::urlize($originalFileName)
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);

        $extension = $uploadedFile->guessExtension();

        $newFileName = $safeFilename.'-'.uniqid().'.'. $extension;

        return $newFileName;
    }
}