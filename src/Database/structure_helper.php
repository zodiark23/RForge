<?php namespace RForge\Database;

/**
 * 
 */
class StructureMapper{
    private $local_columns;
    private $db_columns;

    /**
     * Raw array of the new structure contains only the `add` or `change` or `drop`
     */
    private $raw = [];
    /** Index of added */
    private $index_added = [];
    /** Changed name detected */
    private $index_changed = [];
    /** This is the index of the detected deleted columns in the local columns*/
    private $index_deleted = [];
    /** Temp holder of the strange columns*/
    private $strange = [];


    public function __construct($local, $existing){
        $this->local_columns = $local;
        $this->db_columns = $existing;
        
        $this->detect(); // Detect columns changes
    }
    /**
     * The `column names` are on second index on each array
     * 
     * The `SQL statement` are on first index on each array
     */
    public function structure():array{
        return $this->raw;
    }

    private function difference(){
        return array_unique(array_merge($this->db_columns,$this->local_columns));
    }

    private function detect(){
        $diff = $this->difference();
        $left = array_values(array_diff($diff,$this->local_columns));
        $right = array_values(array_diff($diff,$this->db_columns));
        $this->changed($left, $right);
    }

    /**
     * **Right** arrays are the basis for `CHANGED COLUMNS` & `ADDED COLUMNS`
     * **Left** array are used to detect `DROP COLUMNS`
     */
    private function changed($left, $right){
        foreach($right as $key => $value){
            $left_position = array_search($value,$this->local_columns);

            //# CHANGED
            $exists_on_db_columns = array_key_exists($left_position, $this->db_columns);

            if($exists_on_db_columns){
                $x = $this->db_columns[$left_position];

                if(in_array($x,$left)){
                    array_push($this->raw,array("CHANGE COLUMN ".$x. " ".$value,$value));
                    array_push($this->index_changed,$left_position);
                }
                else{
                    // # ADDED 
                    array_push($this->raw,array("ADD COLUMN ".$value,$value));
                }
            }
            // # ADDED
            else{
                array_push($this->raw,array("ADD COLUMN ".$value,$value));
            }
        }

        foreach($left as $key => $value){
            $right_position = array_search($value,$this->db_columns);

            $condition_1 = false; // If Left exists on db_col
            $condition_2 = false; // If left does NOT exists on local_col

            
            if(in_array($value,$this->db_columns)){
                $condition_1 = true;
            }
            if(!in_array($value,$this->local_columns)){
                $condition_2 = true;
            }
            
            if($condition_1 == true && $condition_2 == true && !in_array($right_position, $this->index_changed)){
                array_push($this->raw , array("DROP ".$value,$value));
            }
        }



    }

 
}



