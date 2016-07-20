<?php
use JoeFallon\KissTest\UnitTest;

require_once('config/main.php');

new \tests\JoeFallon\PhpDatabase\ExampleEntityTests();
new \tests\JoeFallon\PhpDatabase\ExampleEntityGatewayTests();
new \tests\JoeFallon\PhpDatabase\ExampleJoinGatewayTests();

UnitTest::getAllUnitTestsSummary();
