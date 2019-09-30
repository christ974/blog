<?php
namespace App;
use \PDO;

class PaginatedQuery
{
    //propriétés permettant de mémoriser certaines infos
    private $query;
    private $queryCount;
    private $classMapping;
    private $pdo;
    private $perPage;

    public function __construct(
        string $query,
        string $queryCount,
        string $classMapping,
        ?\PDO $pdo = null,
        int $perPage = 12
        )
    {
       $this->query = $query;
       $this->queryCount = $queryCount;
       $this->classMapping = $classMapping;
       $this->pdo = $pdo ?: Connection::getPDO();
       $this->perPage = $perPage; 
    }

    public function getItems(): array
    {
        $page = $_GET['page'] ?? 1;
            if(!filter_var($page, FILTER_VALIDATE_INT))
                {
                    throw new Exception('Numéro de page invalide');
                }
        $currentPage = (int)$page;  
        $count = (int) $this->pdo
            -> query($this->queryCount)
            ->fetch(PDO::FETCH_NUM)[0];
        $pages = ceil($count / $this->perPage);
        if($currentPage > $pages){
            throw new Exception('Page inexistante');
        }
        $offet = $this->perPage * ($currentPage -1);
        return $this->pdo->query(
            $this->query .
            " LIMIT {$this->perPage} OFFSET $offet")
            ->fetchAll(PDO::FETCH_CLASS, $this->classMapping);//on précise la class

    }

    

}