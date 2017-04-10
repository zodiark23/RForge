<?php namespace RForge\Database;

use PDO;
/** 
 * Represents a connection to the database. 
 * Accepts array configuration for connection
 */
class Connection{
    
    protected $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
           ];

    protected   $host;
    protected   $db;
    protected   $user;
    protected   $pass;
    protected   $charset;
    protected   $driver;
    protected   $dsn;

    public function __construct(array $config){
        $this->host    = $config["host"];
        $this->db      = $config["db"];
        $this->user    = $config["user"];
        $this->pass    = $config["pass"];
        $this->charset = $config["charset"];
        $this->driver  = $config["driver"];

        $this->dsn     = $this->driver.":host=".$this->host.";dbname=".$this->db.";charset=".$this->charset;
    }

    public function create(array $opt = null) {
        try{
            return new PDO($this->dsn, $this->user , $this->pass , $this->opt);
        }
        catch(Exception $e){
            echo $e->getMessage();
        }
    }
}





