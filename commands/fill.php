<?php

use App\Connection;

require dirname(__DIR__).'/vendor\autoload.php';

//initalisation faker pour obtenir des données aléatoires
$faker = Faker\Factory::create('fr_FR');

$pdo = Connection::getPDO();

//vidage complet des bdd
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

//création de 2 tabl pour les id pr post et category
$posts = [];
$categories = [];

//boucle pour remplir la base de données
for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET name='{$faker->sentence($nb = 2, $asText = false)}', slug='{$faker->slug}', create_at='{$faker->date} {$faker->time}', content='{$faker->paragraphs(rand(3,15), true)}'");
    $posts[] = $pdo->lastInsertId();
    
}
//on insère les liaisons
for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category SET name='{$faker->sentence(3)}', slug='{$faker->slug}'");
    $categories[] = $pdo->lastInsertId();
}

//une fois récupérer les id,
foreach($posts as $post){
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    foreach($randomCategories as $category){
        $pdo->exec("INSERT INTO post_category SET post_id=$post, category_id=$category");
    }
}

$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$password'");