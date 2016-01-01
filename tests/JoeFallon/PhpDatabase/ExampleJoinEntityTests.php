<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\KissTest\UnitTest;

class ExampleJoinEntityTests extends UnitTest
{
    public function test_initialization()
    {
        $joinEntity = new ExampleJoinEntity();
        $this->assertEqual(0, $joinEntity->getId1());
        $this->assertEqual(0, $joinEntity->getId2());
        $this->assertEqual('', $joinEntity->getCreated());
    }

    public function test_getters_and_setters()
    {
        $joinEntity = new ExampleJoinEntity();
        $joinEntity->setId1(1);
        $joinEntity->setId2(2);
        $joinEntity->setCreated('2012-12-12 12:12:12');

        $this->assertEqual(1, $joinEntity->getId1());
        $this->assertEqual(2, $joinEntity->getId2());
        $this->assertEqual('2012-12-12 12:12:12', $joinEntity->getCreated());
    }
}
