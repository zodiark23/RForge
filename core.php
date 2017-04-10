<?php namespace RForge;

require_once __DIR__."/vendor/autoload.php";

use RForge\Database\User;
use RForge\Database\Connection;
use RForge\Settings\Config;


class Application {
    
    
    public function initConnection(){
        $conn = new Connection(Config::getConnection());
        return $conn->create();
    }
        
}

