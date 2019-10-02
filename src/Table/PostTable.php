<?php

namespace App\Table;

use \PDO;
use App\PaginatedQuery;
use App\Model\Post;
use App\Model\Category;
use App\Table\CategoryTable;

class PostTable extends Table
{
    
public function findPaginated()
{
    $paginatedQuery = new PaginatedQuery(
        "SELECT * FROM post ORDER BY create_at DESC",
        "SELECT COUNT(id) FROM post",
        $this->pdo
    );
    $posts = $paginatedQuery->getItems(Post::class);
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
    
}

public function findPaginatedForCategory($categoryId)
{
    $paginatedQuery = new PaginatedQuery(
        "SELECT p.* 
            FROM post p
            JOIN post_category pc ON pc.post_id = p.id
            WHERE pc.category_id = {$categoryId}
            ORDER BY create_at DESC 
        ",
        "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryId}"
    );

    $posts = $paginatedQuery->getItems(Post::class);
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
    /*
    $postsById = [];
    foreach($posts as $post){
        $postsById[$post->getId()] = $post;
    }
    $categories = $this->pdo
                    ->query('SELECT c.*, pc.post_id
                           FROM post_category pc
                           JOIN category c ON c.id = pc.category_id
                           WHERE pc.post_id IN (' .implode(',',array_keys($postsById)) .')'
                 )->fetchAll(PDO::FETCH_CLASS, Category::class);
    foreach($categories as $category){
        $postsById[$category->getPostId()]->addCategory($category);
    }*/
}


}