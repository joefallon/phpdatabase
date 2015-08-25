<?php
use JoeFallon\KissTest\UnitTest;

require_once('config/main.php');

UnitTest::setCodeCoverageEnabled(true);

new \tests\JoeFallon\PhpDatabase\AbstractJoinTableGatewayTests();
new \tests\JoeFallon\PhpDatabase\AbstractTableGatewayTests();

UnitTest::getAllUnitTestsSummary();
