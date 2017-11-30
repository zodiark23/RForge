<?php namespace RForge\Database;
use RForge\Exception\SystemException;
use RForge\Database\Connection;
use RForge\Database\ClassResolver;
use RForge\Directory\FileCrawler;
use RForge\Database\StructureMapper;
class Database {
    protected $db;
    /**
     * This connection is from the static connection
     */
    public $connection;
    function __construct($dbname = null){
        try{
            if(!$dbname){
                throw new SystemException("Missing Databasename / Connection");
            }else{
                $this->db =$dbname;
                $this->connection = Connection::$connection;
            }
        }catch (SystemException $e){
            $e->present();
        }
        
    }
    /**
     * Create a database if it doesn't exist yet. And Select that DB
     */
    public function createDB(){
        try {
            $databasename = "`".str_replace("`","``",$this->db)."`";
            $this->connection->exec("CREATE DATABASE IF NOT EXISTS $databasename");
            $this->connection->exec("USE $databasename");
        } catch (PDOException $e) {
            die("DB ERROR: ". $e->getMessage());
        }
    }

    /**
     * Select a database to use
     */
    public function selectDB(){
        try {
            $databasename = "`".str_replace("`","``",$this->db)."`";
            $this->connection->exec("USE $databasename");
        } catch (PDOException $e) {
            die("DB ERROR: ". $e->getMessage());
        }
    }

    /* ------------------------------------------
     * |             Load Table                 |
     * ------------------------------------------
     * | Load the table from the path given     |
     * |Namespace is needed. Since directory    |
     * |cannot find files if we include the     |
     * |namepace.                               |
     * | So namespace is loaded from the        |
     * | Application level when initating       |
     * |                                        |
     * | For Example                            |
     * |----------------------------------------|
     * |$app = new Application('MyAppNamespace')|
     * ------------------------------------------ */
    public function loadTables($pathToModelClass , $namespace){
        $directory_files = FileCrawler::dir($pathToModelClass);
        try{
            
            foreach($directory_files as $key){
                    $directory =  key($directory_files);
                foreach($key as $file_name){
                    $class = FileCrawler::getClassFromFile($directory.$file_name);
                    foreach ($class as $class_name) {
                    $each_class = $namespace.'\\'.$directory.$class_name;

                    // ##### MAKE THE TABLES NOW AFTER FINDING ITS PROPERTIES #####
                    $this->createTable($each_class);
                    // ##### RE-UPDATE STRUCTURE - N/A on prod_mode
                    $this->updateTableStructure($each_class);
                    }

                }
            }
        } catch(Exception $e){
            throw new SystemException("Cannot find directory");
        }
    }

    /**
     * Creates a single table. This function will receive an array of tables to configure
     * @DEV_MODE is ON
     */
    private function createTable($tableClass){

        $z = Resolver::resolve($tableClass);
        $this->connection->exec("CREATE TABLE IF NOT EXISTS ". $z->className ."(". Resolver::toString($z) .")");
    }


    /**
     * Update TABLES with the new structure.
     * @DEV_MODE is ON
     */
     private function updateTableStructure($tableClass){
        $z = Resolver::resolve($tableClass);
        
        $stmt = $this->connection->prepare("SHOW COLUMNS FROM ". $z->className);
        $stmt->execute();

        $local_columns = Resolver::columnList($z);
        $exisiting_columns = Resolver::dbColumnList($stmt->fetchAll());
       
        $mapper = new StructureMapper($local_columns, $exisiting_columns);
        $query_string = Resolver::generateUpdateQuery($z,$mapper);
        
        //UPDATING QUERY
        $stmt = $this->connection->prepare("ALTER TABLE ". $z->className . " " . $query_string);
        $stmt->execute();
     }
}