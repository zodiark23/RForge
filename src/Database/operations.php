<?php namespace RForge\Database;

use RForge\Settings\Config;
use RForge\Database\Connection;
use RForge\Database\Resolver;
use RForge\Database\Database;
/**
 * This will handle all sorts of database operations
 */
class Operations {
    
    /**
     * This will hold the child class this will be used to propagate the result of queries back to the child class
     */
    private $temp;
    /**
     * This will hold the data resolved from this class
     */
    private $me;


    public function __construct(){
        $this->temp = $this;
        $this->me = Resolver::self_resolve($this);
    }


    /**
     * Executes a *direct query* on this table
     * @param $fields - The table column value.
     * @param $values - The value corresponding the array of fields.
     * @param $limit - Limits the number of rows return (optional)
     */
    final public function select(array $fields , array $values, int $limit = null){
        $this->temp->{"IDS"} = "SDFDF";
        var_dump($this->temp);
    }

}