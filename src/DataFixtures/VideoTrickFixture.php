<?php

namespace App\DataFixtures;

use App\Domain\Entity\Trick;
use App\Domain\Entity\TrickVideo;
use App\Service\VideoLinkHelper;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VideoTrickFixture extends BaseFixture implements DependentFixtureInterface
{
    /** @var VideoLinkHelper */
    private $videoLinkHelper;

    /**
     * VideoTrickFixture constructor.
     */
    public function __construct(VideoLinkHelper $videoLinkHelper)
    {
        $this->videoLinkHelper = $videoLinkHelper;
    }

    private static $trickVideos = [
        'https://www.youtube.com/embed/9JE9hlkhDok',
        'https://www.youtube.com/watch?v=iOTcr9wKC-o&list=PLY9JrLCZkx375dmwYeEdkC85GeFA5hb0U',
        'https://player.vimeo.com/video/163662857',
        'https://vimeo.com/video/163662857',
    ];

    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 4; ++$i) {
            $finalLink = $this->videoLinkHelper->transformLinkForEmbedIframe(self::$trickVideos[$i]);
            $trickVideo = new TrickVideo($finalLink);
            $this->addReference(TrickVideo::class . '_' . $i, $trickVideo);
            $manager->persist($trickVideo);

            $trickVideo->setTrick($this->getRandomReference(Trick::class));
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [TrickFixtures::class];
    }
}
