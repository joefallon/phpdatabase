<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\KissTest\UnitTest;

class ExampleEntityTests extends UnitTest
{
    public function test_class_initialization()
    {
        $entity = new ExampleEntity();
        $this->assertEqual($entity->getId(),       0);
        $this->assertEqual($entity->getName(),     "");
        $this->assertEqual($entity->getNullable(), "");
        $this->assertEqual($entity->getNumeral(),  0);
        $this->assertEqual($entity->getCreated(),  "");
        $this->assertEqual($entity->getUpdated(),  "");
    }

    public function test_getters_and_setters()
    {
        $entity = new ExampleEntity();
        $entity->setId(2);
        $entity->setName("test name");
        $entity->setNullable("not null");
        $entity->setNumeral(3);
        $entity->setCreated("2012-12-12 12:12:12");
        $entity->setUpdated("2012-12-13 13:13:13");

        $this->assertEqual($entity->getId(),       2);
        $this->assertEqual($entity->getName(),     "test name");
        $this->assertEqual($entity->getNullable(), "not null");
        $this->assertEqual($entity->getNumeral(),  3);
        $this->assertEqual($entity->getCreated(),  "2012-12-12 12:12:12");
        $this->assertEqual($entity->getUpdated(),  "2012-12-13 13:13:13");
    }
}
