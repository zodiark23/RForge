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
    private $index_didChanged = [];
    /** This is the index of the detected deleted columns in the local columns*/
    private $index_deleted = [];
    /** Temp holder of the strange columns*/
    private $strange = [];


    public function __construct($local, $existing){
        $this->local_columns = $local;
        $this->db_columns = $existing;
        var_dump($local);
        var_dump($existing);
        
        $this->isDeleted(); // Detect removed columns
        $this->didChanged(); // Detect additional columns
    }

    private function difference(){
        return array_unique(array_merge($this->db_columns,$this->local_columns));
    }

    private function didChanged(){
        $diff = $this->difference();
        var_dump($diff);
        $left = array_values(array_diff($diff,$this->local_columns));
        $right = array_values(array_diff($diff,$this->db_columns));
        var_dump($left);
        var_dump($right);
    }

    private function isDeleted(){
        
    }
}



