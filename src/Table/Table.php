<?php
namespace App\Table;
use \PDO;

class Table
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
}