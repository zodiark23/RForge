<?php namespace RForge\Directory;
/**
 * File Operation Class
 * Provides various methods for dealing system level schenanigans
 */
class FileSystem{

    public static function dir($directory){
        $dir = array_diff(scandir($directory), array(".",".."));
        $list = [];
        return $list[$directory] = $dir;
    }
    
}

/**
 * This class is used for fetching class name on a directory
 * Typically used in searching model architecture.
 * This class will be tightly coupled on Database Class for creating tables
 */
class FileCrawler extends FileSystem{
    public static function file_get_php_classes($filepath) {
        $php_code = file_get_contents($filepath);
        $classes = self::get_php_classes($php_code);
        return $classes;
    }

    public static function get_php_classes($php_code) {
        $classes = array();
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if (   $tokens[$i - 2][0] == T_CLASS
                && $tokens[$i - 1][0] == T_WHITESPACE
                && $tokens[$i][0] == T_STRING) {

                $class_name = $tokens[$i][1];
                $classes[] = $class_name;
            }
        }
        return $classes;
    }
}