<?php
use App\Helpers\Text;
use App\Model\Post;
use App\Connection;

$title = 'Mon blog';

$pdo = Connection::getPDO();

$page = $_GET['page'] ?? 1;
if(!filter_var($page, FILTER_VALIDATE_INT))
{
    throw new Exception('Numéro de page invalide');
}
//var de la pge courante
$currentPage = (int)$page;
//si currentPage est égal à 0 ou inférieur, on renvoie une erreur
if($currentPage <= 0){
    throw new Exception('Numéro de page invalide');
}
//dd($currentPage);
//récupère le n° d'articles
$count = (int) $pdo-> query('SELECT COUNT(id) FROM post')->fetch(PDO::FETCH_NUM)[0];
//dd($count) soit 50 article;
$perPage = 12;
//il faut connaitre le nbre e page
$pages = ceil($count / $perPage);
if($currentPage > $pages){
    throw new Exception('Page inexistante');
}
//dd($pages);
$offet = $perPage * ($currentPage -1);
$query = $pdo->query("SELECT * FROM post ORDER BY create_at DESC LIMIT $perPage OFFSET $offet");

$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);//important de préciser la class Post::class
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
    <?php if($currentPage > 1): ?>
        <a href="<?= $router->url('accueil') ?>?page=<?= $currentPage - 1 ?> "class="btn btn-info" >&laquo; Page précédente</a>
    <?php endif ?>
    <?php if($currentPage < $pages): ?>
        <a href="<?= $router->url('accueil') ?>?page=<?= $currentPage + 1 ?> "class="btn btn-info ml-auto" > Page suivante &raquo;</a>
    <?php endif ?>
</div>

