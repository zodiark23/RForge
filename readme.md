# RForge Framework (*on-going*)

The idea is to create an easy to use framework that don't need to follow certain file structure. We will use the `Application ()` class to load its needed modules such as `routes`, `controllers`, `models`. The model class directly represents the table in the database. 

This framework is *under development*. Will offer MVC Pattern architecture with components, and routing and ORM. Model classes will directly reflect the DB Tables.

### To start a new project
> On terminal, navigate to your project's directory and run the following code:
````php
    composer require r-forge/r-forge
````
### Sample application
````php
    require_once __DIR__."/vendor/autoload.php";

    use RForge\Application;
    use RForge\Settings\Config;

    Config::setConnection([
        "host"=>"127.0.0.1",
        "user"=>"root",
        "pass"=>"",
        "charset"=>"utf8",
        "driver"=>"mysql"
    ]);

    $app = new Application("YourProjectNameSpace");
    $app->setTables('Models\\'); //<-- Directory path to your models
    $app->database('bld'); // <-- DB Name
    $app->start(); //<-- Start loading the configurations
````
> **Note**: You must set the config first before running `$app->start()`, or it will use the default configuration

# Models

Models directly represents the database structure. By design we can use these model to fetch data according to their structure. *Methods are not yet implemented*. To create your `model` simply create a new class then assign *properties* to that class. The class properties will be the name of the `columns` created in the database. To assign the datatype use the the `JSDOC` syntax as shown below :

````php
class User{
    /**
     * @INT (10)
     * @AUTO_INCREMENT
     * @PRIMARY KEY
     */
    public $IDS;
    /**
     * @Text
     */
    public $name;
}


````

# Assigning Models path
You must place these model on a separate folder and should be at the upmost directy. As the `filecrawler` will find other classes on these directory too.

 * App
    * Models
        * user.php <-- User model
        * product.php <-- Product model
    * img
    * css
    * js
    * index.php

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



