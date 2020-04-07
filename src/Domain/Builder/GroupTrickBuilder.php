<?php


namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\GroupTrickBuilderInterface;
use App\Domain\Builder\Interfaces\TrickImageBuilderInterface;
use App\Domain\DTO\TrickImageDTO;
use App\Domain\Entity\GroupTrick;
use App\Domain\Entity\TrickImage;
use App\Service\ImageFileNameService;
use App\Service\UploaderHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GroupTrickBuilder implements GroupTrickBuilderInterface
{
    /**
     * @var GroupTrick
     */
    private $groupTrick;

    /**
     * @param string $groupNumber
     * @return GroupTrickBuilder
     */
    public function create(string $groupNumber): self
    {
        $this->groupTrick = new GroupTrick();

        return $this;
    }

    /**
     * @return GroupTrick
     */
    public function getGroupTrick(): GroupTrick
    {
        return $this->groupTrick;
    }
}