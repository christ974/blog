<?php 
require '../vendor/autoload.php';

//$router = new AltoRouter();

define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

//système permettant de détecter si on a un n° de page =1 et qu'on redirige ss ce param
if(isset($_GET['page']) && $_GET['page'] === '1')
{
    //on coupe la string 
     $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
     //on évite d'utiliser une globale
     $get = $_GET;
    //détruit la variable $get
     unset($get['page']);
     // Génère une chaîne de requête en encodage URL
     $query = http_build_query($get);
     if(!empty($query)){
         $uri = $uri . '?' . $query;
     }
     //Récupère ou définit le code de réponse HTTP
     http_response_code(301);
     //redirection
     header('Location: ' . $uri);
     //sortie
     exit();
    //dd($uri);
}

$router = new App\Router(dirname(__DIR__) . '/views');
$router->get('/', '/post/index', 'accueil');
$router->get('/blog/category/[*:slug]-[i:id]', '/category/show','category');
$router->get('/blog/[*:slug]-[i:id]', 'post/show','post');
$router->get('/admin', 'admin/post/index', 'admin_posts');
$router->get('/admin/post/[i:id]','admin/post/edit','admin_post');
$router->post('/admin/post/[i:id]/delete','admin/post/delete','admin_post_delete');
$router->get('/admin/post/new','admin/post/new','admin_new');
$router->run();

