# RForge Framework (*on-going*)

The idea is to create an easy to use framework that don't need to follow certain file structure. We will use the `Application ()` class to load its needed modules such as `routes`, `controllers`, `models`. The model class directly represents the table in the database. 

This framework is *under development*. Will offer MVC Pattern architecture with components, and routing and ORM. Model classes will directly reflect the DB Tables.
````php
    To start a new project.

    Config::setConnection([
        "host"=>"127.0.0.1",
        "user"=>"root",
        "pass"=>"",
        "charset"=>"utf8",
        "driver"=>"mysql"
    ]);

    $app = new Application();
    $app->start(); <-- Start loading the configurations
    $app->database('bld'); // <-- DB Name
````
> **Note**: You must set the config first before running APP->start(), or it will use the default configuration

## Application Structure
 * src
    * App
    * Config
    * Controller
    * Database
    * Directory
    * Exception
    * Model
    * Provider
    * Session
    * Component
    * Route



