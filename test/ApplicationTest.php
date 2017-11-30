<?php
require "vendor/autoload.php";
require 'src/App/app.php';
require 'src/Config/config.php';
use RForge\Application;

/**
 * PHPUnit version 3.7.21
 */

class ApplicationTest extends PHPUnit_Framework_TestCase{

    private $app;

    protected function setUp(){
       
        $this->app = new Application("test");
    }

    protected function tearDown(){
        $this->app = NULL;
    }

    public function testAppNamespace(){
        $result = $this->app->namespace;

        $this->assertNotEquals("", $result);
        
    }
    
}