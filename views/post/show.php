<?php

use App\Connection;
use App\Model\Post;
use App\Model\Category;

$title = "L'article";

/**Récupération de l'article correspondant à l'id et au slug */
//on récup l'id
$id = (int)$params['id'];
//récup url
$slug = $params['slug'];
//dd($params);
//connection
$pdo = Connection::getPDO();
//req préparer pr récupérer 1 id
$query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);
/**@var Post|false */
$post = $query->fetch();

if($post === false){
    throw new Exception("Aucun article correspond à l'Id demandé.");
}

if($post->getSlug() !== $slug){
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
    //on précise que c'est une redirection permanente
    http_response_code(301);
    //redirection
    header('Location: ' . $url);
}
/**Récupération des catégories de l'article correspondant  */
//nouvelle req préparée, alias pc pour post-category
$query = $pdo->prepare('
SELECT c.id, c.slug, c.name 
FROM post_category pc
JOIN category c ON pc.category_id = c.id
WHERE pc.post_id = :id ');
$query->execute(['id' => $post->getId()]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
$categories = $query->fetchAll();
//dd($categories);

?>
<h1><?= htmlentities($post->getName()) ?></h1>

<div class="row center">
    <p class="text-info">En date du  <?= $post->getCreateAt()->format('d/m/Y') ?>.  </p>
    <p>
        <?= $post->getFormatedContent() ?>
    </p>
    <p>
        <h5 class="text-secondary">Categorie(s) de l'article : </h5>
    <?php foreach($categories as $category): ?>
    <a href="<?= $router->url('category', ['id'=>$category->getId(), 'slug'=>$category->getSlug()] ) ?>" class="text-warning"> <?= htmlentities($category->getNames()) . ' - ' ?></a>  
    <?php endforeach ?> 
    </p>  
    <p>
        <a href="/" class="text-muted">Retour à l'accueil</a>
    </p>
</div>