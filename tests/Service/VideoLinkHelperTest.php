<?php


namespace App\Tests\Service;

use App\Service\VideoLinkHelper;
use PHPUnit\Framework\TestCase;

class VideoLinkHelperTest extends TestCase
{
    public function testLinkYoutube()
    {
        $link = 'https://www.youtube.com/watch?v=iOTcr9wKC-o&list=PLY9JrLCZkx375dmwYeEdkC85GeFA5hb0U';
        $videoLinkHelper = new VideoLinkHelper();
        $finalLink = $videoLinkHelper->transformLinkForEmbedIframe($link);

        $this->assertSame('https://www.youtube.com/embed/iOTcr9wKC-o', $finalLink);
    }

    public function testLinkDailyMotion()
    {
        $link = 'https://www.dailymotion.com/video/x7tf3li?playlist=x5nmbq';
        $videoLinkHelper = new VideoLinkHelper();
        $finalLink = $videoLinkHelper->transformLinkForEmbedIframe($link);

        $this->assertSame('https://www.dailymotion.com/embed/video/x7tf3li', $finalLink);
    }

    public function testLinkVimeo()
    {
        $link = 'https://vimeo.com/159611530';
        $videoLinkHelper = new VideoLinkHelper();
        $finalLink = $videoLinkHelper->transformLinkForEmbedIframe($link);

        $this->assertSame('https://player.vimeo.com/video/159611530', $finalLink);
    }

    public function testLinkIframeYoutube()
    {
        $link = '<iframe width="560" height="315" src="https://www.youtube.com/embed/iOTcr9wKC-o" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        $videoLinkHelper = new VideoLinkHelper();
        $finalLink = $videoLinkHelper->transformLinkForEmbedIframe($link);

        $this->assertSame('https://www.youtube.com/embed/iOTcr9wKC-o', $finalLink);
    }

    public function testLinkIframeDailyMotion()
    {
        $link = '<iframe frameborder="0" width="640" height="360" src="https://www.dailymotion.com/embed/video/x7tf1tg" allowfullscreen allow="autoplay"></iframe>';
        $videoLinkHelper = new VideoLinkHelper();
        $finalLink = $videoLinkHelper->transformLinkForEmbedIframe($link);

        $this->assertSame('https://www.dailymotion.com/embed/video/x7tf1tg', $finalLink);
    }

    public function testLinkIframeVimeo()
    {
        $link = '<iframe src="https://player.vimeo.com/video/159611530?color=FFED00&title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
<p><a href="https://vimeo.com/159611530">Oxmo Puccino - Le Marteau et la Plume</a> from <a href="https://vimeo.com/biscuitstudio">Biscuit Studio</a> on <a href="https://vimeo.com">Vimeo</a>.</p>';
        $videoLinkHelper = new VideoLinkHelper();
        $finalLink = $videoLinkHelper->transformLinkForEmbedIframe($link);

        $this->assertSame('https://player.vimeo.com/video/159611530?color=FFED00&title=0&byline=0&portrait=0', $finalLink);
    }
}