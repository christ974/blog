<?php 

namespace App;

use AltoRouter;

class Router
{
    private $viewPath;
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null){
        $this->router->map('GET', $url, $view, $name);
    }

    public function url(string $name, array $params =[])
    {
        return $this->router->generate($name, $params);

    }

    public function run()
    {
        $match = $this->router->match();
        $view = $match['target'];

        //dd($match);
        $params = $match['params'];//on conserve ls params de l'url
        $router = $this;
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR. $view . '.php';
        $content = ob_get_clean();
        require $this->viewPath. DIRECTORY_SEPARATOR.'layouts/default.php';
    }
}