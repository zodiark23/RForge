<?php namespace RForge\Database;

use ReflectionClass;
use ReflectionProperty;
use RForge\Database\StructureMapper;
use RForge\Exception\SystemException;
/**
 * Table Resolver
 * `resolve` method Converts class into readable array
 * > Note : You must call `resolve` first before using its other method.
 * Creates a new class with the array structure
 * This array will be used to structure the Query for creating Table or updating Table
 */
class Resolver{
    static function resolve(string $classname){
        $resolved = [];

        $reflect = new ReflectionClass($classname);
        if(!$reflect) { throw new SystemException("Unable to resolve this class"); }
        $method = $reflect->getProperties();

        foreach($method as $key){
            $summary = array();
            $property = new ReflectionProperty($classname , $key->name);
            $raw =  str_replace("/","",str_replace("*","",$property->getDocComment()));
            $dirty = ltrim(rtrim(preg_replace('/\s\s+/', ' ',preg_replace( "/\r|\n/", "", $raw )))); // Clean the white spaces
            $metadata = array_filter(explode("@",$dirty));
            $summary["property_name"] = $key->name;
            $summary["metadata"] = $metadata;
            array_push($resolved , $summary);
        }
        
        $class = new ResolvedClass();
        $class->className = $reflect->getShortName();
        $class->properties = $resolved;
        
        return $class;
    }

    /**
     * **NOTE:** Run the resolver first before using this command
     * Converts a class **PROPERTIES** into a string with its meta data 
     * Separated by default delimiter `,`
     * Warning ClassName is not included in this string
     */
    public static function toString(ResolvedClass $z,$divider = ", "){
        $string = [];
        foreach($z->properties as $property){
            $per_property = $property["property_name"]." ";
            foreach($property["metadata"] as $key){
                $per_property  .= str_pad($key,1," ",STR_PAD_BOTH);
            }

            array_push($string , $per_property);
        }

        return implode($divider,$string);
    }

    /**
     * Generate the query structure for updating
     */
    public static function generateUpdateQuery(ResolvedClass $z, StructureMapper $sm){
        $structure = $sm->structure();
        $query_string = [];
        foreach($structure as $key => $value){
            $one_column_query = $value[0] ." ". Resolver::attr($z, $value[1]);
            if(strpos($one_column_query,"PRIMARY KEY") !== false){
                $one_column_query = "DROP PRIMARY KEY , ".$one_column_query;
            }
            array_push($query_string, $one_column_query);
        }

        return implode(" , ",$query_string);
    }


    /** 
    * Returns an array of column names base on the resolved class
    */
    public static function columnList(ResolvedClass $z){
        $columns = [];
        foreach($z->properties as $column){
            array_push($columns,$column['property_name']);
        }
        return $columns;
    }

    /**
    * Returns array of column names base on the array given from the query on the database.
    */
    public static function dbColumnList(array $array){
         $db_columns = [];
         foreach($array as $row){
            array_push($db_columns,$row['Field']);
        }
        return $db_columns;
    }
    /**
     * Returns the attribute in respect to the property name
     * @value is column you wish to retrieve the attribute
     */
    private static function attr(ResolvedClass $z, $value = ''){
        foreach($z->properties as $property){
            if($property["property_name"] == $value){
                return implode(" ",$property["metadata"]);
            }
        }
    }
}

/**
 * This class is used for return type
 * A model class only for referencing the type of return
 */
class ResolvedClass{
    public $className; 
    public $properties;
}




