<?php

use App\Connection;
use App\Table\PostTable;

$title = 'Mon blog';

$pdo = Connection::getPDO();

/*Utilisation de la class PostTable, f° findPaginated()
$paginatedQuery = new PaginatedQuery(
    "SELECT * FROM post ORDER BY create_at DESC",
    "SELECT COUNT(id) FROM post"
);
$posts = $paginatedQuery->getItems(Post::class);

//extraction de l'id correspondant entre ls tables category et post_category
$postsById = [];
foreach($posts as $post){
    $postsById[$post->getId()] = $post;
    //$ids[] = $post->getId();
}

//seconde requête
$categories = $pdo
                ->query('SELECT c.*, pc.post_id
                       FROM post_category pc
                       JOIN category c ON c.id = pc.category_id
                       WHERE pc.post_id IN (' .implode(',',array_keys($postsById)) .')'
             )->fetchAll(PDO::FETCH_CLASS, Category::class);
//liaison entre ls catégories et le tableau d'articles
//$postsById[getPostId()]

foreach($categories as $category){
    $postsById[$category->getPostId()]->addCategory($category);
}*/

$table = new PostTable($pdo);
/*utilisationde la f° list pr simplifier
$var = $table->findPaginated();
$posts = $var[0];
$pagination = $var[1];*/
list($posts, $pagination) =  $table->findPaginated();

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
    <?= $pagination->previousLink($link) ?>
   <?= $pagination->nextLink($link) ?>
</div>

