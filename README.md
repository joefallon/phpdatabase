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

*   An entity is a class that represents a single row within a database.
*   All entities are subclasses of `AbstractEntity`.
*   All abstract entities contain the following data fields:
    1.  `id` - This is the primary key of the row.
    2.  `created` - This is the date and time that the row was created.
    3.  `updated` - This is the date and time that the row was last updated.
    