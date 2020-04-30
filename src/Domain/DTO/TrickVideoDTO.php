<?php

namespace App\Domain\DTO;

use App\Domain\Entity\TrickVideo;
use Symfony\Component\Validator\Constraints as Assert;

class TrickVideoDTO
{
    /** @var id */
    protected $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    protected $pathUrl;

    /**
     * TrickVideoDTO constructor.
     */
    public function __construct(?string $pathUrl = '')
    {
        $this->setPathUrl($pathUrl);
    }

    public static function createFromEntity(TrickVideo $video, $y)
    {
        $dto = new self();

        $dto->setId($y);
        $dto->setPathUrl($video->getPathUrl());

        return $dto;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getPathUrl(): ?string
    {
        return $this->pathUrl;
    }

    public function setPathUrl(?string $pathUrl): void
    {
        $this->pathUrl = $pathUrl;
    }
}
