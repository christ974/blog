<?php

use App\Connection;
use App\Table\PostTable;
//use App\Auth;

//Auth::check();

$title ="Administration";
$pdo = Connection::getPDO();
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();
$link = $router->url('admin_posts');
?>
<?php if (isset($_GET['delete'])) ?>
<div class="alert alert-success">
    L'article a été supprimé.
</div>
<table class="table table-striped">
    <thead>
    <th>#</th>
        <th>Titre</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php foreach($posts as $post): ?>
        <tr>
            <td>#<?= $post->getId() ?></td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getId()]) ?>">
                <?= htmlentities($post->getName()) ?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getId()]) ?>" class="btn btn-info ml-auto">
                Editer
                </a>
                <form action="<?= $router->url('admin_post_delete', ['id' => $post->getId()]) ?>" style="display:inline;" method="POST"  onsubmit="return confirm('Voulez-vous effectuer cette action ?')">
                
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>    
            </td>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>

<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link) ?>
   <?= $pagination->nextLink($link) ?>
</div>