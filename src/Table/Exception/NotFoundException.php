<?php

namespace App\Table\Exception;

class NotFoundException extends \Exception
{
    public function __construct(string $table, int $id)
    {
        $this->message = "Aucun enregistrement ne correspond à l'article #$id dans la table '$table' ";
    }
    
}