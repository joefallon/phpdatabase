<?php
use JoeFallon\KissTest\UnitTest;

require_once('config/main.php');

new \tests\JoeFallon\PhpDatabase\ExampleEntityTests();

//new \tests\JoeFallon\PhpDatabase\AbstractJoinTableGatewayTests();
//new \tests\JoeFallon\PhpDatabase\AbstractTableGatewayTests();

UnitTest::getAllUnitTestsSummary();
