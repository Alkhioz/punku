<?php
require_once('../controllers/controllerManager.php');

class router{

    private $controllerManager;
    private $routes;
        
    public function __CONSTRUCT(){
        $this->controllerManager = new controllerManager();
        $this->routes = json_decode(file_get_contents("../router/routes.json"), true);
    }
    
    public function getUri(){
        return rtrim($_SERVER['REQUEST_URI'], '/');
    }

    public function checkRoute(){
        $uri = $this->getUri();
        return !array_key_exists($uri, $this->routes) ? '/error' : $uri;
    }

    public function start(){
        $controller_name=$this->routes[$this->checkRoute()]["controller"];
        $action_name=$this->routes[$this->checkRoute()]["action"];
        $this->controllerManager->executeAction($controller_name, $action_name);
    }
    
}