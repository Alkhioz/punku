<?php

class defaultController{
        
    public function __CONSTRUCT(){
        
    }
    
    public function Index(){
        require_once '../views/header.php';
        require_once '../views/default.php';
        require_once '../views/footer.php';
    }
    
}