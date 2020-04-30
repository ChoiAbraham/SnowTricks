<?php

namespace App\Service;

class VideoLinkHelper
{
    private $finalLink = '';

    public function transformLinkForEmbedIframe($link): string
    {
        // Youtube
        $youtubeRegex = '/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((?:\w|-){11})(?:&list=(\S+))?$/';
        if (preg_match($youtubeRegex, $link, $matches)) {
            $this->finalLink = 'https://www.youtube.com/embed/' . $matches[1];
        }

        // Vimeo
        $vimeoRegex = "/vimeo\.com\/(\w+\s*\/?)*([0-9]+)*$/i";
        if (preg_match($vimeoRegex, $link, $matches)) {
            $this->finalLink = 'https://player.vimeo.com/video/' . $matches[1];
        }

        // Dailymotion
        $dailymotionRegex = "/(.+)dailymotion.com\/video\/([\w-]+)/";
        if (preg_match($dailymotionRegex, $link, $matches)) {
            $this->finalLink = 'https://www.dailymotion.com/embed/video/' . $matches[2];
        }

        // Iframes
        if (preg_match('/<iframe\s.*?\bsrc="(.*?)".*?>/si', $link, $matches)) {
            $this->finalLink = $matches[1];
        }

        return $this->finalLink;
    }
}
