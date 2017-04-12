<?php namespace RForge;

use RForge\Database\Database;
use RForge\Database\Connection;
use RForge\Settings\Config;
use RForge\Directory\FileSystem;

/**
 * Create a new instance of RForge application.
 * **Don't forget to set  the configuration settings before initializing**.
 */
class Application {
    /**
     * By default application will be on dev mode
     * Certain behavior of application will behave
     * depending on the value of this.
     * For example : On prod mode updating of tables is prohibited
     */
    protected $production = false;
    /**
     * Represents a database connection
     * 
     */
    protected $connection;
    /**
     * Start the application
     */
    public function start(){
        $this->initConnection();
    }

    /**
     * Start a connection to the database
     */
    public function initConnection(){
        $this->connection = Connection::init();
    }
    /**
     * Select the database to interact with
     * If it doesn't exist it will be created
     * Use the instance of connection this application
     */
    public function database(string $databasename = ""){
        $db = new Database($databasename);
        $db->createDB();
        $db->selectDB();
        return $db;
    }

    /**
     * Responsible for autoloading the tables inside that directory.
     * In theory it should receive a namespace for the classes
     * Then it will manage the database tables and update it accordingly
     */
    public function tables(){

    }

    /**
     * Set the routing for this application
     */
    public function setRoute(Route $route){

    }

    /**
     * Register the components to use in this application.
     * View 
     */
    public function setComponents(Component $component){

    }

    /**
     * Render the view that is requested base on the route that was provided.
     * Controllers are also loaded.
     * After Loading the controller the needed components will be loaded automatically.
     */
    private function render(View $view){

    }



        
}

