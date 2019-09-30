<?php
use App\Helpers\Text;
use App\Model\Post;
use App\Connection;
use App\PaginatedQuery;

$title = 'Mon blog';

$pdo = Connection::getPDO();

$paginatedQuery = new PaginatedQuery(
    "SELECT * FROM post ORDER BY create_at DESC",
    "SELECT COUNT(id) FROM post"
    
);
$posts = $paginatedQuery->getItems(Post::class);
$link =$router->url('accueil');
?>
<h1>Le blog</h1>

<div class="row center">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require 'card.php' ?>
    </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousLink($link) ?>
   <?= $paginatedQuery->nextLink($link) ?>
</div>

