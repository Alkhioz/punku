<?php

class controllerManager{

    private $controller;
    private $action;
        
    public function __CONSTRUCT(){
    
    }

    public function executeAction($controller_name, $action_name){   
        $this->constructController($controller_name);
        $this->constructAction($action_name);
        call_user_func( array( $this->controller, $this->action));
    }

    public function constructController($controller_name){
        $name = $this->checkController($controller_name);
        require_once "../controllers/$name.controller.php";
        $customController = ucwords($name) . 'Controller';
        $this->controller= new $customController;
    }

    public function constructAction($action_name){
        $this->action= $this->checkAction($action_name);
    }
    
    public function checkController($controller_name){
        return file_exists("../controllers/$controller_name.controller.php") ? $controller_name : 'Error';
    }

    public function checkAction($action_name){
       if (!is_callable(array($this->controller, $action_name))){
            $this->constructController('Error');
            return 'Index';
        }
        return $action_name;
    }

}