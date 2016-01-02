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

    public function test_baseUpdate()
    {
        $entity1 = new ExampleEntity();
        $entity1->setName('test name');
        $entity1->setNullable('test nullable');
        $entity1->setNumeral(3);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $id = $entityGateway->create($entity1);
        $entity1 = $entityGateway->retrieve($id);
        $entity1->setName('new name');
        $entity1->setNullable('new nullable');
        $entity1->setNumeral(4);

        $rowsAffected = $entityGateway->update($entity1);
        $this->assertEqual(1, $rowsAffected, 'rows affected');

        $entity2 = $entityGateway->retrieve($id);
        $this->assertEqual($entity1->getId(),       $entity2->getId());
        $this->assertEqual($entity1->getName(),     $entity2->getName());
        $this->assertEqual($entity1->getNullable(), $entity2->getNullable());
        $this->assertEqual($entity1->getNumeral(),  $entity2->getNumeral());
        $this->assertEqual($entity1->getCreated(),  $entity2->getCreated());
    }

    public function test_baseDelete()
    {
        $entity = new ExampleEntity();
        $entity->setName('test name');
        $entity->setNullable('test nullable');
        $entity->setNumeral(3);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $id = $entityGateway->create($entity);

        $rowsAffected = $entityGateway->delete($id);
        $this->assertEqual(1, $rowsAffected);

        $out = $entityGateway->retrieve($id);
        $this->assertEqual(null, $out);
    }

    public function test_baseRetrieveBy()
    {
        $entity1 = new ExampleEntity();
        $entity1->setName('baseRetrieveBy');
        $entity1->setNullable('test nullable 1');
        $entity1->setNumeral(3);

        $entity2 = new ExampleEntity();
        $entity2->setName('baseRetrieveBy');
        $entity2->setNullable('test nullable 2');
        $entity2->setNumeral(4);

        $entity3 = new ExampleEntity();
        $entity3->setName('baseRetrieveBy');
        $entity3->setNullable('test nullable 3');
        $entity3->setNumeral(5);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $entityGateway->create($entity1);
        $entityGateway->create($entity2);
        $entityGateway->create($entity3);

        $results = $entityGateway->retrieveByName('baseRetrieveBy');
        $this->assertEqual(3, count($results));
    }

    public function test_baseRetrieveByIds()
    {
        $entity1 = new ExampleEntity();
        $entity1->setName('baseRetrieveByIds');
        $entity1->setNullable('test nullable 1');
        $entity1->setNumeral(3);

        $entity2 = new ExampleEntity();
        $entity2->setName('baseRetrieveByIds');
        $entity2->setNullable('test nullable 2');
        $entity2->setNumeral(4);

        $entity3 = new ExampleEntity();
        $entity3->setName('baseRetrieveByIds');
        $entity3->setNullable('test nullable 3');
        $entity3->setNumeral(5);

        $ids = [];

        $entityGateway = ExampleEntityGatewayFactory::create();
        $ids[] = $entityGateway->create($entity1);
        $ids[] = $entityGateway->create($entity2);
        $ids[] = $entityGateway->create($entity3);

        $results = $entityGateway->retrieveByIds($ids);
        $this->assertEqual(3, count($results));
    }

    public function test_baseRetrieveByIsNull()
    {
        $entityIn1 = new ExampleEntity();
        $entityIn1->setName('baseRetrieveByIsNull1');
        $entityIn1->setNullable(null);
        $entityIn1->setNumeral(3);

        $entityIn2 = new ExampleEntity();
        $entityIn2->setName('baseRetrieveByIsNull2');
        $entityIn2->setNullable(null);
        $entityIn2->setNumeral(4);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $entityGateway->create($entityIn1);
        $entityGateway->create($entityIn2);
        $results = $entityGateway->retrieveByIsNull();

        $this->assertEqual(2, count($results));
    }

    public function test_baseRetrieveByNotEqual()
    {
        $entityIn1 = new ExampleEntity();
        $entityIn1->setName('baseRetrieveByNotEqual1');
        $entityIn1->setNullable(null);
        $entityIn1->setNumeral(3);

        $entityIn2 = new ExampleEntity();
        $entityIn2->setName('baseRetrieveByNotEqual2');
        $entityIn2->setNullable(null);
        $entityIn2->setNumeral(4);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $entityGateway->truncateTable();
        $entityGateway->create($entityIn1);
        $entityGateway->create($entityIn2);
        $results = $entityGateway->retrieveByNameNotEqual('non-existing name');

        $this->assertEqual(2, count($results));
    }

    public function test_baseSetFieldNull()
    {
        $entityIn1 = new ExampleEntity();
        $entityIn1->setName('baseSetFieldNull');
        $entityIn1->setNullable('not null');
        $entityIn1->setNumeral(5);

        $entityIn2 = new ExampleEntity();
        $entityIn2->setName('baseSetFieldNull');
        $entityIn2->setNullable('not null');
        $entityIn2->setNumeral(6);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $entityGateway->truncateTable();
        $entityGateway->create($entityIn1);
        $entityGateway->create($entityIn2);
        $affectedRows = $entityGateway->setFieldNullableNull('not null');

        $this->assertEqual(2, $affectedRows);
    }

    public function test_baseDeleteBy()
    {
        $entityIn1 = new ExampleEntity();
        $entityIn1->setName('baseDeleteBy');
        $entityIn1->setNullable('not null');
        $entityIn1->setNumeral(5);

        $entityIn2 = new ExampleEntity();
        $entityIn2->setName('baseDeleteBy');
        $entityIn2->setNullable('not null');
        $entityIn2->setNumeral(6);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $entityGateway->create($entityIn1);
        $entityGateway->create($entityIn2);

        $affectedRows = $entityGateway->deleteNameBy('baseDeleteBy');
        $this->assertEqual(2, $affectedRows);
    }

    public function test_baseCountBy()
    {
        $entityIn1 = new ExampleEntity();
        $entityIn1->setName('baseCountBy');
        $entityIn1->setNullable('not null');
        $entityIn1->setNumeral(5);

        $entityIn2 = new ExampleEntity();
        $entityIn2->setName('baseCountBy');
        $entityIn2->setNullable('not null');
        $entityIn2->setNumeral(6);

        $entityGateway = ExampleEntityGatewayFactory::create();
        $entityGateway->create($entityIn1);
        $entityGateway->create($entityIn2);

        $count = $entityGateway->countByName('baseCountBy');
        $this->assertEqual(2, $count);
    }
}
