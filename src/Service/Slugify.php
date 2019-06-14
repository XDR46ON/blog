<?php

namespace App\Service;

class Slugify
{
    public function generate(string $input) :string
    {
        // transformation des 'à' 'é' 'etc' en charactères normaux
        $input = iconv('UTF-8', 'ASCII//TRANSLIT', $input);
        // on supprime les charactères spéciaux et ponctuation
        $input = preg_replace('/[!-,]/', '', $input);
        // suppression des espaces eventuels en début et fin de chaine
        $input = trim($input);
        // on remplace les espace par des tirets
        $input = str_replace(' ', '-', $input);
        return strtolower($input);
    }
}