<?php
use JoeFallon\AutoLoader;
use JoeFallon\KissTest\UnitTest;
use JoeFallon\PhpDatabase\PdoFactory;


// Define the include paths.
define('BASE_PATH', realpath(dirname(__FILE__).'/../../'));
define('SRC_PATH',  BASE_PATH.'/src');
define('VEND_PATH', BASE_PATH.'/vendor');

// Set the application include paths for autoloading.
set_include_path(get_include_path().':'.SRC_PATH.':'.BASE_PATH);

require(VEND_PATH.'/autoload.php');
AutoLoader::registerAutoLoad();

UnitTest::setCodeCoverageOutputDirectory('../cov');
UnitTest::addDirectoryToCoverageBlacklist('../tests');
UnitTest::addDirectoryToCoverageBlacklist('../vendor');

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_USER', 'phpdatabase_test');
define('DB_PASS', 'phpdatabase_test');
define('DB_NAME', 'phpdatabase_test');

/** @var PDO $pdo */
$pdo = PdoFactory::create(DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME);
$pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
$pdo->exec('TRUNCATE TABLE `gtwy_tests`');
$pdo->exec('TRUNCATE TABLE `join_tests`');
$pdo->exec('SET FOREIGN_KEY_CHECKS=1;');


