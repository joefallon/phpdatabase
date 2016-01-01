<?php
use JoeFallon\KissTest\UnitTest;

require_once('config/main.php');
UnitTest::setCodeCoverageEnabled(false);

new \tests\JoeFallon\PhpDatabase\ExampleEntityTests();
new \tests\JoeFallon\PhpDatabase\ExampleJoinEntityTests();

new \tests\JoeFallon\PhpDatabase\ExampleEntityGatewayTests();




UnitTest::getAllUnitTestsSummary();
