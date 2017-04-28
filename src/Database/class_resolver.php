<?php namespace RForge\Database;

use ReflectionClass;
use ReflectionProperty;
use RForge\Exception\SystemException;
/**
 * Table Resolver
 * Converts class into readable array
 * Creates a new class with the array structure
 * This array will be used to structure the Query for creating Table
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
}

/**
 * This class is used for return type
 * A model class only for referencing the type of return
 */
class ResolvedClass{
    public $className; 
    public $properties;
}




