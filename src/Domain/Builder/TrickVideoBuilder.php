<?php


namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\TrickVideoBuilderInterface;
use App\Domain\DTO\TrickVideoDTO;
use App\Domain\Entity\TrickVideo;

class TrickVideoBuilder implements TrickVideoBuilderInterface
{
    /**
     * @var TrickVideo
     */
    private $trickVideo;

    /**
     * @param string $content
     * @return TrickVideo
     */
    public function create(TrickVideoDTO $dto): self
    {
        $this->trickVideo = new TrickVideo(
            $dto->getPathUrl()
        );

        return $this;
    }

    /**
     * @return TrickVideo
     */
    public function getTrickVideo(): TrickVideo
    {
        return $this->trickVideo;
    }
}