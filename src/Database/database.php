<?php namespace RForge\Database;

class User {
    protected $db;
    function __construct($dbname){
        $this->db = $dbname;
    }
}