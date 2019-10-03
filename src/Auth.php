<?php

namespace App;

use Exception;

class Auth
{
    public function check()
    { if(!isset($_GET['admin']))
        {
            throw new Exception('Accès refusé ! Merci de vous identifier.');
        }

    }
}