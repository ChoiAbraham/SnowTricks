<?php

namespace App\Service;


class VideoLinkHelper
{
    private $finalLink;

    public function transformLinkForEmbedIframe($link): string
    {
//        if(preg_match(
//            '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/',
//            $link,
//            $matches)) {
//            $this->finalLink = 'https://www.youtube.com/embed/' . $matches[1];
//        }
//
//        if(preg_match("/vimeo\.com\/(\w+\s*\/?)*([0-9]+)*$/i",
//            $link,
//            $matches)) {
//            $this->finalLink = 'https://player.vimeo.com/video/' . $matches[1];
//        }

        if(preg_match('/http:\/\/www\.dailymotion\.com\/video\/+/', $link, $matches)) {
            dd($matches);
        }

        return $this->finalLink;
    }
}