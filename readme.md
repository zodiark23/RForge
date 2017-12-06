# RForge Framework (*on-going*) [![Build Status](https://travis-ci.org/zodiark23/RForge.png?branch=master)](https://travis-ci.org/zodiark23/RForge)

The idea is to create an easy to use framework for beginners. We will use the `Application ()` class to load its needed modules such as `routes`, `controllers`, `models`. The model class directly represents the table in the database. 

This framework is *under development*. Will offer MVC Pattern architecture with components, and routing and ORM. Model classes will directly reflect the DB Tables.

### To start a new project
> On terminal, navigate to your project's directory and run the following code:
````php
    composer require rforge/rforge
````
>Then run this command to generate new autoloader
````php
    composer dump-autoload -o
````
### Sample application
When you completed all the required steps above you can start creating a new application. This is a sample format of your `index.php`. Call the `require_once` line as shown below to load the framework. After that line we need to import the Application class by `use Rforge\Application;`. 

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

Models directly represents the database structure. _RForge tracks the changes on your models and updates the database_. The framework does not support Model Relational Database Mapping yet.

To create your `model` simply create a new class then assign *properties* to that class. The class properties will be the name of the `columns` created in the database. To assign the datatype use the the `JSDOC` syntax as shown below :

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

# Operations

These class holds the database method implementation and must be inherited by your models.

```php
class User extends Operations{
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
```
## Using your models
Some of the models return the value to the Model that trigger it. While other methods return the value as an array of that Model. Currently only two methods is supported and more methods will be available soon.

Initialize your model and use the methods below:
- selectAll(array $limit) 
    - Returns all the value of the database
    - TODO: customizing the query for order, group etc
- findByID(mixed $id)
    - Returns the row of the that matches the primary of that table
```php
$user = new User(); // initialize the model with operations
$user->findByID(23); // invoke the method
echo $user->name; //output => Alex
```
> Notice that you directly get the value of your query on your model. *Some methods **returns an array of the Model that triggered the method** , while others return the value directly **to the class itself***

# Assigning Models path
You must place these model on a separate folder and should be at the upmost directy. As the `filecrawler` will find other classes on these directory too.

 * App
    * Models <-- This is the path you must put at `setTables` method in application class.
        * user.php <-- User model
        * product.php <-- Product model
    * img
    * css
    * js
    * index.php

## Framework Structure(Developer Reference)
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



