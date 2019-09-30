<?php

namespace App\Helpers;

use \DateTime;

class Text 
{
    public static function excerpt(string $content, int $limit = 40)
    {
        if (mb_strlen($content) <= $limit)//la f° mb_strlen permet de prdre en cpte des caractères copmm smiley
        {
            return $content;
        }
        $lastSpace = mb_strpos($content, ' ', $limit);// si string trp grd strpos donne la position d'une chaîne, ici l'espace
        return mb_substr($content, 0, $lastSpace) . ' ...' ;//dc on dde couper au dernier espace
    
    }
    public function getCreateAt(): DateTime
    {
        return new DateTime($this->create_at);
    }
}