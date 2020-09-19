<?php
require_once '../models/user.php';

class userController{
    
    private $model;

    public function __CONSTRUCT(){
        $this->model = new user();
    }
    
    public function Index(){
        require_once '../views/header.php';
        require_once '../views/default.php';
        echo "<br> USER PAGE";
        require_once '../views/footer.php';
        
    }

    public function Login(){
        $this->model->setUsername("admin@email.com");
        $this->model->setPassword("000000");
        echo $this->model->login();
    }
    
}