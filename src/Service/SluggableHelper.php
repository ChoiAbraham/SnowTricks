<?php


namespace App\Service;


class SluggableHelper
{
    CONST DELIMITER = '-';

    public function createSlug($title) : string
    {
        $unwanted_array = ['é'=>'e', 'è' => 'e']; // French letters
        $title = strtr( $title, $unwanted_array );

        $slug = strtolower(
            trim(
            preg_replace(
            '/[\s-]+/', self::DELIMITER,
            preg_replace('/[^A-Za-z0-9-]+/', self::DELIMITER,
                preg_replace('/[&]/', 'and',
                    preg_replace('/[\']/', '',
                        iconv('UTF-8', 'ASCII//TRANSLIT', $title))))),
            self::DELIMITER));
        return $slug;
    }
}