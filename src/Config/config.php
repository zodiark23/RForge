<?php namespace RForge\Settings;

/**
 * Sets the application level configuration
 *
 */
class Config{

    public static $connectionOptions = [
                "host"=>"127.0.0.1",
                "db"=>"blog",
                "user"=>"root",
                "pass"=>"",
                "charset"=>"utf8",
                "driver"=>"mysql"
            ];
    /**
     * Return the Configuration for connections
     * @return array
     */
    public static function getConnection(){
        return self::$connectionOptions;
    }

    public static function setConnection(array $conn_opts){
        self::$connectionOptions = $conn_opts;
    }       

}