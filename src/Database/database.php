<?php namespace RForge\Database;
use RForge\Exception\SystemException;
use RForge\Database\Connection;
use RForge\Database\ClassResolver;
class Database {
    protected $db;
    /**
     * This connection is from the static connection
     */
    protected $connection;
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

    /**
     * Creates a single table. This function will receive an array of tables to configure
     */
    private function createTable($tableClass){

        $z = Resolver::resolve($tableClass);
        $this->connection->exec("CREATE TABLE IF NOT EXISTS ". $z->className ."(". Resolver::toString($z) .")");
    }
}