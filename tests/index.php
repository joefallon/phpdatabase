<?php
use JoeFallon\KissTest\UnitTest;

require_once('config/main.php');

new \tests\JoeFallon\Database\AbstractJoinTableGatewayTests();
new \tests\JoeFallon\Database\AbstractTableGatewayTests();

UnitTest::getAllUnitTestsSummary();