<?php


namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\TrickImageBuilderInterface;
use App\Domain\DTO\TrickImageDTO;
use App\Domain\Entity\TrickImage;
use App\Service\ImageFileNameService;
use App\Service\UploaderHelper;
use http\Exception\BadMessageException;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TrickImageBuilder implements TrickImageBuilderInterface
{
    /**
     * @var TrickImage
     */
    private $trickImage;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    /**
     * TrickImageBuilder constructor.
     * @param UploaderHelper $uploaderHelper
     */
    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    /**
     * @param string $content
     * @return TrickImage
     */
    public function create(TrickImageDTO $dto): self
    {
        /** @var UploadedFile $uploadFile*/
        $uploadFile = $dto->getImage();
        if(!is_null($uploadFile)) {
            $newFileName = ImageFileNameService::generateFileName($uploadFile);
            $this->uploaderHelper->uploadTrickImage($uploadFile, $newFileName);
        } else {
            throw new \RuntimeException('File does not exist.');
        }

        $this->trickImage = new TrickImage(
            $newFileName,
            $dto->getFirstimage(),
            $dto->getAlt()
        );

        return $this;
    }

    /**
     * @return TrickImage
     */
    public function getTrickImage(): TrickImage
    {
        return $this->trickImage;
    }
}