<?php
use JoeFallon\AutoLoader;
use JoeFallon\PhpDatabase\PdoFactory;

// Define the include paths.
define('BASE_PATH', realpath(dirname(__FILE__).'/../../'));
define('SRC_PATH',  BASE_PATH.'/src');
define('VEND_PATH', BASE_PATH.'/vendor');

// Set the application include paths for autoloading.
set_include_path(get_include_path().':'.SRC_PATH.':'.BASE_PATH);

require(VEND_PATH.'/autoload.php');
AutoLoader::registerAutoLoad();

define('DB_USER', 'phpdatabase_test');
define('DB_PASS', 'phpdatabase_test');
define('DB_NAME', 'phpdatabase_test');

/** @var PDO $pdo */
$pdo = PdoFactory::create(DB_NAME, DB_USER, DB_PASS);
$pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
$pdo->exec('TRUNCATE TABLE `example_entity_table`');
$pdo->exec('TRUNCATE TABLE `example_join_table`');
$pdo->exec('SET FOREIGN_KEY_CHECKS=1;');


