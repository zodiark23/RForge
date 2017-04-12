<?php namespace RForge\Database;

use PDO;
use RForge\Settings\Config;
/** 
 * Represents a connection to the database. 
 * Accepts array configuration for connection
 * Only one instance of this class will run through out the application.
 */
class Connection{
    public static $connection;
    protected static $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
           ];

    protected static   $host;
    protected static   $user;
    protected static   $pass;
    protected static   $charset;
    protected static   $driver;
    protected static   $dsn;

    public static function init(array $opt = null){
        $config = Config::getConnection();
        self::$host    = $config["host"];
        self::$user    = $config["user"];
        self::$pass    = $config["pass"];
        self::$charset = $config["charset"];
        self::$driver  = $config["driver"];

        self::$dsn     = self::$driver.":host=".self::$host.";charset=".self::$charset;
  
        $con = new PDO(self::$dsn, self::$user , self::$pass , self::$opt);
        self::$connection = $con;
        return $con;
    }

}







