# phpdatabase

A simple library for MySQL database access. It has the following features:

*   Full suite of unit tests.
*   It can be integrated into any existing project.
*   Can be fully understood in just a few moments.

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

#### Fourth Level Item

### Abstract Join-Table Gateway

### PDO Factory
