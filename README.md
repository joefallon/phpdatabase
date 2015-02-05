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

*   `AbstractEntity`
*   `AbstractTableGateway`
*   `AbstractJoinTableGateway`
*   `PdoFactory`

### Entities

An entity is a class that represents a single row within a database. All entities 
are subclasses of `AbstractEntity`. All abstract entities contain the following 
data fields:

*   `id` - This is the primary key of the row.
*   `created` - This is the date and time that the row was created.
*   `updated` - This is the date and time that the row was last updated.
    
Additional data fields are added to `AbstractEntity`. Each data field that is 
added to `AbstractEntity` and should correlate one-to-one with columns within a 
given table. 

In addition to the data fields, each subclass of `AbstractEntity` also
contains the `isValid` valid. The `isValid` method returns true when the entity is
considered valid and can be stored within the database. When an entity is considered
invlid, the entity should store messages within the entity. The messages are
retrieved using the `getValidationMessages` method. The messages should be written
in a way as to be usable for possible display to the user.

### Abstract Table Gateway

Instances of subclasses of `AbstractTableGateway` are used to mediate all access to
a table within the database. 

Each subclass must implement the abstract methods
`convertObjectToArray` and `convertArrayToObject`. The method `convertObjectToArray`
is used to convert an entity to an associative array. The names of the keys map
to the culumn names within the database. The method `convertArrayToObject` is
used to convert an associative array that was retrieved from the database into
an object.

Additionally, several methods are provided to assist with access to the database.
There are four major methods that are used to provide the basic CRUD (i.e. Create,
Retrieve, Update, Delete) access to the database. The the following methods are
used to provide public access:

*   `baseCreate(AbstractEntity $entity)`
*   `baseRetrieve($id)`
*   `baseUpdate(AbstractEntity $entity)`
*   `baseDelete($id)`

### Abstract Join-Table Gateway

The class `AbstractJoinTableGateway` is used mediate access to join tables 
(i.e. junction table). These tables to represent many-to-many associations.

### PDO Factory

The `PdoFactory` factory class is used to create a PHP `PDO` object.
