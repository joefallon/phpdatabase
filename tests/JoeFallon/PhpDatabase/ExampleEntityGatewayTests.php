<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\KissTest\UnitTest;

class ExampleEntityGatewayTests extends UnitTest
{
    public function test_baseCreate_and_baseRetrieve()
    {
        $entityIn = new ExampleEntity();
        $entityIn->setName('test name');
        $entityIn->setNullable('test nullable');
        $entityIn->setNumeral(3);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $id = $entityGateway->create($entityIn);
        $this->assertFirstLessThanSecond(0, $id, 'id is zero');

        $entityOut = $entityGateway->retrieve($id);
        $this->assertEqual($id,             $entityOut->getId()              );
        $this->assertEqual('test name',     $entityOut->getName()            );
        $this->assertEqual('test nullable', $entityOut->getNullable()        );
        $this->assertEqual(3,               $entityOut->getNumeral()         );
        $this->assertEqual(19,              strlen($entityOut->getCreated()) );
        $this->assertEqual(19,              strlen($entityOut->getUpdated()) );
    }

//    public function test_baseUpdate()
//    {
//        $this->notImplementedFail();
//    }
}
