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
    /**
     * Connection to the database. This is used to perform PDO database actions
     */
    private $connection;
    /**
     * This is the classname of this class. Which represents the table of itself.
     */
    private $table;
    /**
     * The name of the Primary Key of the table
     */
    private $primary_key;

    final public function __construct(){
        $this->temp = $this;
        $this->me = Resolver::self_resolve($this);

        $this->table = strtolower($this->me->className);
        $this->primary_key = $this->detectPrimaryKeyName($this->me->properties);

        $db = new Database(Config::$dbname);
        $db->selectDB();
        $this->connection = $db->connection;
    }


    /**
     * *Selects* all data on this table
     *  You can pass 2 values on parameter `limit` to mimic "LIMIT 0 , 2"
     *
     *  *e.g* **selectAll( [0, 2] )**
     *
     * **Note** : Returns an array (TODO : Collection)
     * @param array $limit - Limits the number of rows return (optional)
     */
    final public function selectAll($limit = []){
        $limit = count($limit) > 0 ? " LIMIT ".implode(", ", $limit) : "";
        $query = $this->connection->prepare("SELECT * FROM ". $this->table );
        $query->execute([]);
        $result = $query->fetchAll();
        
        $objects = $this->finalize($result);

        return $objects;
    }

    /**
     * Fetch the data of this primary key on this table.
     * @param $primary - The primary key of this table.
     */
    final public function findByID($primary){
        
        $query = $this->connection->prepare("SELECT * FROM ". $this->table . " WHERE `".$this->primary_key."`=:query_value LIMIT 1");
        $query->execute(["query_value" => $primary]);
        $result = $query->fetchAll();

        $objects = $this->finalize($result);
        
        return $objects;
        
    }

    /**
     * Assign the value to it self must NOT contain multi dimension array
     *
     * *Note : This can be refactored when the implementation of Collection is complete*
     * @param array $value - The value to be assign on this class
     * @return void
     */
    public function selfAssign(array $value){
        array_map(function($value,$key){$this->temp->{$key} = $value;},$value,array_keys($value));
    }

    /**
     * Extract the primary key name on the resolved property array
     * 
     * @return string $pk_name - Primary Key that this table uses;
     */
    private function detectPrimaryKeyName(array $resolved_properties){
        foreach($resolved_properties as $property){
            foreach($property['metadata'] as $key => $metadata){
                if ( $metadata == 'PRIMARY KEY')
                    return $property['property_name'];
            }
        }
    }

    /**
     * Finalize the result and transform it for returning object
     * 
     * Note: Must be used after running query
     * 
     * If the result rows is only one, no array is created
     * 
     * @param array $result - The result to be transform into object
     * @return array<stdClass> $objects - Array of StdClass objects
     */
    private function finalize(array $result){
        if(empty($result)){
            return false;
        }
        $objects = array();

        foreach($result as $table_row){
            /* Create new instanced object */
            $temporary_self = new $this;
            $temporary_self->selfAssign($table_row);
            $objects[] = $temporary_self;
            if(count($result) == 1){
                $objects = $temporary_self;
            }
            /* To prevent heavy memory usage */
            unset($temporary_self);
        }
        

        return $objects;
    }

}