<?php
use App\Connection;
use App\Model\{Category, Post};
use App\PaginatedQuery;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$query = $pdo->prepare('SELECT * FROM category WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
/**@var Post|false */
$category = $query->fetch();

if($category === false){
    throw new Exception("Aucune catégorie de correspond a cette Id.");
}

if($category->getSlug() !== $slug){
    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
    //on précise que c'est une redirection permanente
    http_response_code(301);
    //redirection
    header('Location: ' . $url);
}

$title = "Catégorie : {$category->getNames()}";
//dd($category);

$paginatedQuery = new PaginatedQuery(
    "SELECT p.* 
        FROM post p
        JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = {$category->getId()}
        ORDER BY create_at DESC 
    ",
    "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}",
    Post::class
);
/**@var Post[] */
$posts = $paginatedQuery->getItems();
//dd($posts);
/*TOUT CE CODE A ETE INTRODUIT DS LA CLASSE PAGINATEDQUERY!!!*/
/*récupération de la page courante
$page = $_GET['page'] ?? 1;
if(!filter_var($page, FILTER_VALIDATE_INT))
{
    throw new Exception('Numéro de page invalide');
}
$currentPage = (int)$page;

if($currentPage <= 0){
    throw new Exception('Numéro de page invalide');
}*/

/**récupération des noms d'articles correspondant à cette catégorie */
/*$count = (int) $pdo
-> query('SELECT COUNT(category_id) FROM post_category WHERE category_id =' . $category->getId())
->fetch(PDO::FETCH_NUM)[0];

$perPage = 12;
$pages = ceil($count / $perPage);
if($currentPage > $pages){
    throw new Exception('Page inexistante');
}
$offet = $perPage * ($currentPage -1);
$query = $pdo->query("SELECT p.* 
                      FROM post p
                      JOIN post_category pc ON pc.post_id = p.id
                      WHERE pc.category_id = {$category->getId()}
                      ORDER BY create_at DESC 
                      LIMIT $perPage OFFSET $offet
                      ");

$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);//important de préciser la class Post::class*/

$link = $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]);
//dd($link);
?>


<h1><?= htmlentities($title)  ?></h1>

<!--on veut lister les articles correspondants à cette catégorie-->
<div class="row center">
    <?php foreach($posts as $post): ?>
    <div class="col-md-3">
        <?php require dirname(__DIR__) . '/post/card.php' ?>
    </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
   <?= $paginatedQuery->previousLink($link) ?>
   <?= $paginatedQuery->nextLink($link) ?>
    
</div>


