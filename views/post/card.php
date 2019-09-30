
<div class="card mb-5">
            <div class="card-body" >
                <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
                
                <p class="text-info">- <?= $post->getCreateAt()->format('d/m/Y') ?> - 

<?php foreach($post->getCategories() as $category): ?> 
                     <a href="<?= $router->url('category', ['id'=>$category->getId(), 'slug'=>$category->getSlug()] ) ?>"   class="text-warning"> <?= htmlentities($category->getNames()) . '     ' ?></a>  
                <?php endforeach ?> </p>
                <p>
                    <?= $post->getExcerpt() ?>
                </p>
                <p>
                <a href="<?= $router->url('post', ['id'=> $post->getId(), 'slug' => $post->getSlug()]) ?>" class="btn btn-info ml-auto">Voir plus</a>
                </p>
            </div>        
</div>