<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\KissTest\UnitTest;

class ExampleJoinGatewayTests extends UnitTest
{
    public function test_baseCreate_and_baseRetrieve()
    {
        $id1 = 2;
        $id2 = 3;

        $joinGateway = ExampleJoinGatewayFactory::create();
        $rowsAffected = $joinGateway->create($id1, $id2);
        $this->assertEqual(1, $rowsAffected);

        $row = $joinGateway->retrieve($id1, $id2);
        $this->assertEqual(2, (int)$row['id1']);
        $this->assertEqual(3, (int)$row['id2']);
        $this->assertEqual(19, strlen($row['created']));
    }

    public function test_delete()
    {
        $id1 = 4;
        $id2 = 5;

        $joinGateway = ExampleJoinGatewayFactory::create();
        $joinGateway->create($id1, $id2);
        $rowsAffected = $joinGateway->delete($id1, $id2);
        $this->assertEqual(1, $rowsAffected);

        $row = $joinGateway->retrieve($id1, $id2);
        $this->assertEqual(0, count($row));
    }

    public function test_baseRetrieveById()
    {
        $id1 = 6;
        $id2 = 7;

        $joinGateway = ExampleJoinGatewayFactory::create();
        $joinGateway->create($id1, $id2);

        $row = $joinGateway->retrieveById1($id1);
        $this->assertEqual(1, count($row));
        $this->assertEqual(6, (int)$row[0]['id1']);
        $this->assertEqual(7, (int)$row[0]['id2']);
        $this->assertEqual(19, strlen($row[0]['created']));
    }

    public function test_baseDeleteById()
    {
        $id1 = 8;
        $id2 = 9;

        $joinGateway = ExampleJoinGatewayFactory::create();
        $joinGateway->create($id1, $id2);

        $rowsAffected = $joinGateway->deleteById1($id1);
        $this->assertEqual(1, $rowsAffected);

        $row = $joinGateway->retrieve($id1, $id2);
        $this->assertEqual(0, count($row));
    }
}
