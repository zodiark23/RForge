<?php namespace RForge\Settings;

/**
 * Sets the application level configuration
 *
 */
class Config{

    /**
     * Default connection settings
     */
    private static $connectionOptions = [
                "host"=>"127.0.0.1",
                "user"=>"root",
                "pass"=>"",
                "charset"=>"utf8",
                "driver"=>"mysql"
            ];
    public static $production = false;

    public static $dbname;
    /**
     * Return the Configuration for connections
     * @return array
     */
    public static function getConnection(){
        return self::$connectionOptions;
    }
    /**
     * Set a connection with a custom settings
     * @return void
     */
    public static function setConnection(array $conn_opts){
        self::$connectionOptions = $conn_opts;
    }       

}