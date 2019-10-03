<?php
namespace App\Table;
use \PDO;
use App\Model\Category;
use App\Table\Exception\NotFoundException;

final class CategoryTable extends Table
{
    //protected $table = 'category';
    //protected $class = Category::class;
    public function find ($id)
    {
        $query = $this->pdo->prepare('SELECT * FROM category WHERE id = :id');
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, Category::class);
        
        $result = $query->fetch();
        if($result === false){
            throw new NotFoundException('category',$id);
        }
        return $result;
    }

    public function hydratePosts($posts)
    {
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
        }
    }


}