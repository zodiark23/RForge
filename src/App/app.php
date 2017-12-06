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
     * Current instance of the application database created
     */
    public $database;
    protected $path_to_model_directory;
    public $namespace;
    

    public function __construct($namespace){
        $this->namespace = $namespace;
        $this->production = Config::$production;
    }
    /**
     * Start the application
     */
    public function start(){
        $this->initConnection();
        $this->startDatabase();
        $this->loadTables($this->path_to_model_directory);
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
    private function startDatabase(){
        Config::$dbname = $this->database;
        $db = new Database($this->database);
        $db->createDB();
        $db->selectDB();
        $this->db = $db;
        return $db;
    }

    /**
     * Set which path to use on loading table from class.
     * This is need because start() function will initialize the connection
     */
    public function setTables(string $path_to_model_directory){
        $this->path_to_model_directory= $path_to_model_directory;
    }
    /**
     * Responsible for autoloading the tables inside that directory.
     * In theory it should receive a namespace for the classes
     * Then it will manage the database tables and update it accordingly
     */
    public function loadTables(string $path_to_model_directory){
        $locked = file_exists(".rflock");
        if($this->production == false || ($this->production == true && $locked == false) ){
            $this->db->loadTables($path_to_model_directory, $this->namespace);
            $lockFile = fopen('.rflock',"w") or die("Unable to open file!");
        }
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

