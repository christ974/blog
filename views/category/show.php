<?php
use App\Connection;
use App\Model\{Category, Post};
use App\PaginatedQuery;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
/*
Méthode find de CategoryTable
$query = $pdo->prepare('SELECT * FROM category WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
*@var Post|false *
$category = $query->fetch();*/

$categoryTable = new CategoryTable($pdo);
$category = $categoryTable->find($id);

/* renvoyer ds la class NotFoundExcetion
if($category === null){
    throw new Exception("Aucune catégorie de correspond a cette Id.");
}*/

if($category->getSlug() !== $slug){
    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
    //on précise que c'est une redirection permanente
    http_response_code(301);
    //redirection
    header('Location: ' . $url);
}

$title = "Catégorie : {$category->getNames()}";
//dd($category);

/*$paginatedQuery = new PaginatedQuery(
    "SELECT p.* 
        FROM post p
        JOIN post_category pc ON pc.post_id = p.id
        WHERE pc.category_id = {$category->getId()}
        ORDER BY create_at DESC 
    ",
    "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}"
);
**@var Post[] *
$posts = $paginatedQuery->getItems(Post::class);


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


foreach($categories as $category){
    $postsById[$category->getPostId()]->addCategory($category);
}*/
[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginatedForCategory($category->getId());
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


