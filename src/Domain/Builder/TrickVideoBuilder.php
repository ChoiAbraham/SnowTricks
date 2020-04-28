<?php


namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\TrickVideoBuilderInterface;
use App\Domain\DTO\TrickVideoDTO;
use App\Domain\Entity\TrickVideo;
use App\Service\VideoLinkHelper;

class TrickVideoBuilder implements TrickVideoBuilderInterface
{
    /**
     * @var TrickVideo
     */
    private $trickVideo;

    /** @var VideoLinkHelper */
    private $videoLinkHelper;

    /**
     * TrickVideoBuilder constructor.
     * @param VideoLinkHelper $videoLinkHelper
     */
    public function __construct(VideoLinkHelper $videoLinkHelper)
    {
        $this->videoLinkHelper = $videoLinkHelper;
    }

    /**
     * @param string $content
     * @return TrickVideo
     */
    public function create(TrickVideoDTO $dto): self
    {
        $this->trickVideo = new TrickVideo(
            $this->getEmbedPathUrl($dto->getPathUrl())
        );

        return $this;
    }

    public function getEmbedPathUrl($path) {
        return $this->videoLinkHelper->transformLinkForEmbedIframe($path);
    }

    /**
     * @return TrickVideo
     */
    public function getTrickVideo(): TrickVideo
    {
        return $this->trickVideo;
    }
}