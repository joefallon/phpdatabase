# phpdatabase

By [Joe Fallon](http://blog.joefallon.net)

A simple library for MySQL database access. It has the following features:

*   Full suite of unit tests.
*   It can be integrated into any existing project.
*   Can be fully understood in just a few moments.
*   The library implements the data mapper design patter (a.k.a. table gateway).

## Installation

The easiest way to install PhpDatabase is with
[Composer](https://getcomposer.org/). Create the following `composer.json` file
and run the `php composer.phar install` command to install it.

```json
{
    "require": {
        "joefallon/phpdatabase": "*"
    }
}
```

## Usage

There are four main classes are are used to represent all of the relationships
within a database: 

*   `AbstractTableGateway`
*   `AbstractJoinTableGateway`
*   `PdoFactory`

### Entities

An entity is a class that represents a single row within a database. All entities 
must contain a `primary key` feild and optionally a `created at` and `updated at` field:

*   `primary key` - This is the primary key of the row. It can be named anything. 
*   `created at` - This is the date and time that the row was created. It can be named anything.
*   `updated at` - This is the date and time that the row was last updated. It can be named anything.
    
Additional data fields are added to the entity. Each data field that is 
added to the entity and should correlate one-to-one with columns within a 
given table. 

### Abstract Table Gateway

Instances of subclasses of `AbstractTableGateway` are used to mediate all access to
a table within the database. 

Each subclass must implement the abstract methods
`mapObjectToArray` and `mapArrayToObject`. The method `mapObjectToArray`
is used to convert an entity to an associative array. The names of the keys map
to the column names within the database. The method `mapArrayToObject` is
used to convert an associative array that was retrieved from the database into
an object.

Additionally, several methods are provided to assist with access to the database.
There are four major methods that are used to provide the basic CRUD (i.e. Create,
Retrieve, Update, Delete) access to the database. The the following methods are
used to provide public access:

*   `baseCreate($entity)`
*   `baseRetrieve($id)`
*   `baseUpdate($entity)`
*   `baseDelete($id)`

### Abstract Join-Table Gateway

The class `AbstractJoinTableGateway` is used mediate access to join tables 
(i.e. junction table). These tables to represent many-to-many associations.

### PDO Factory

The `PdoFactory` factory class is used to create a PHP `PDO` object.

### Usage

Please refer to the [unit tests](https://github.com/joefallon/phpdatabase/tree/master/tests)
for a detailed example of how to use this package.
