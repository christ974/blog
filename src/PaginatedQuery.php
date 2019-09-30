<?php
namespace App;
use \PDO;

class PaginatedQuery
{
    //propriétés permettant de mémoriser certaines infos
    private $query;
    private $queryCount;
    private $pdo;
    private $perPage;
    private $count;
    private $items;//permet d'éviter de renouveller la dde $posts

    public function __construct(
        string $query,
        string $queryCount,
        ?\PDO $pdo = null,
        int $perPage = 12
        )
    {
       $this->query = $query;
       $this->queryCount = $queryCount;
       $this->pdo = $pdo ?: Connection::getPDO();
       $this->perPage = $perPage; 
    }

    public function getItems(string $classMapping): array
    {
       if($this->items === null){
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage > $pages){
            throw new \Exception('Page inexistante');
        }
        $offet = $this->perPage * ($currentPage -1);
        $this->items = $this->pdo->query(
            $this->query .
            " LIMIT {$this->perPage} OFFSET $offet")
            ->fetchAll(PDO::FETCH_CLASS, $classMapping);//on précise la class
       }
       return $this->items;
    }

    public function previousLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= "?page="  .  ($currentPage - 1);
        return <<<HTML
            <a href="{$link}" class="btn btn-info">&laquo; Page précédente</a>
HTML;
    }

    public function nextLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
            <a href="{$link}" class="btn btn-info">Page suivante &raquo;</a>
HTML;
    }

    //création d'une méthode privée pr récupérer la page courante
    private function getCurrentPage(): int
    {
        $page = $_GET['page'] ?? 1;
            if(!filter_var($page, FILTER_VALIDATE_INT))
                {
                    throw new Exception('Numéro de page invalide');
                }
        return $currentPage = (int)$page;
    }
    //création d'une méthode privée pr connaitre le nbre de pages
    private function getPages()
    {
        if($this->count === null){
            $this->count = (int) $this->pdo
                -> query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
        }
        
        return ceil($this->count / $this->perPage);
    }
}