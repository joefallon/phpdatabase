<?php
namespace tests\JoeFallon\Database;


use InvalidArgumentException;
use JoeFallon\KissTest\UnitTest;
use JoeFallon\Log\Log;
use JoeFallon\Time\Chronograph;

use PDO;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
class AbstractTableGatewayTests extends UnitTest
{
    /**
     * getGatewayWithRealLogger
     * 
     * @return ConcreteTableGateway
     */
    private function getGatewayWithRealLogger()
    {
        /** @var $pdo PDO */
        global $pdo;

        $path   = realpath(BASE_PATH . '/tests/logs') . '/' . date('Y-m-d') . '.log';
        $logger = new Log($path, Log::DEBUG);
        $timer  = new Chronograph();
        $tableName = 'gtwy_tests';
        $gtwy      = new ConcreteTableGateway($pdo, $tableName, $timer, $logger);
        
        return $gtwy;
    }

    public function test_baseCountBy_returns_zero_when_no_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        $count = $gtwy->countByNullableValue(9999999);

        $this->assertEqual($count, 0);
    }

    public function test_baseCountBy_returns_correct_value()
    {
        $gtwy = $this->getGatewayWithRealLogger();

        $entity1 = new GtwyTestEntity();
        $entity1->_name = 'John Doe5';
        $entity1->_nullable_val = 999999;

        $id1 = $gtwy->create($entity1);

        $entity2 = new GtwyTestEntity();
        $entity2->_name = 'John Doe6';
        $entity2->_nullable_val = 999999;

        $id2 = $gtwy->create($entity2);

        $count = $gtwy->countByNullableValue(999999);

        $this->assertEqual($count, 2);

        $gtwy->delete($id1);
        $gtwy->delete($id2);
    }

    
    /**************************************************************************
     * baseCreate Tests
     **************************************************************************/

    public function test_baseCreate_updates_timestamps()
    {
        $entity = new GtwyTestEntity();
        $entity->_name = 'John Doe';
        $entity->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($entity);
        
        $createdLen = strlen($entity->getCreated());
        $updatedLen = strlen($entity->getUpdated());
        
        $this->assertFirstGreaterThanSecond($createdLen, 0);
        $this->assertFirstGreaterThanSecond($updatedLen, 0);
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
    
    public function test_baseCreate_throws_InvalidArgumentException_on_invalid_entity()
    {
        try
        {
            $entity = new GtwyTestEntity();
            $entity->_name = null;
            $entity->_nullable_val = 100;

            $gtwy = $this->getGatewayWithRealLogger();
            $id = $gtwy->create($entity);
        }
        catch(InvalidArgumentException $ex)
        {
            $this->testPass();
            return;
        }

        $this->testFail();
    }
    
    public function test_baseCreate_returns_last_insert_id_on_insertion()
    {
        $entity = new GtwyTestEntity();
        $entity->_name = 'John Doe';
        $entity->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($entity);
        
        $this->assertFirstGreaterThanSecond($id, 0);
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
    
    public function test_baseCreate_returns_updates_entity_on_insertion()
    {
        $entity = new GtwyTestEntity();
        $entity->_name = 'John Doe';
        $entity->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($entity);
        
        $this->assertEqual($id, $entity->getId());
        
        // Perform clean-up.
        $gtwy->delete($id);
    }

    public function test_baseCreate_properly_inserts_row()
    {
        $in = new GtwyTestEntity();
        $in->_name = 'John Doe';
        $in->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($in);
        
        $out = $gtwy->retrieve($in->getId());
        
        $this->assertEqual($in->getId(),       $out->getId());
        $this->assertEqual($in->getCreated(),  $out->getCreated());
        $this->assertEqual($in->getUpdated(),  $out->getUpdated());
        $this->assertEqual($in->_name,         $out->_name);
        $this->assertEqual($in->_nullable_val, intval($out->_nullable_val));
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
    
    /**************************************************************************
     * baseDelete Tests
     **************************************************************************/
    
    public function test_baseDelete_returns_zero_if_id_not_found()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        $rowsAffected = $gtwy->delete(100000000);
        
        $this->assertEqual($rowsAffected, 0);
    }

    public function test_baseDelete_properly_deletes_row()
    {
        $in = new GtwyTestEntity();
        $in->_name = 'John Doe';
        $in->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($in);
        
        // Perform clean-up.
        $gtwy->delete($id);
        
        $out = $gtwy->retrieve($in->getId());
        $this->assertEqual($out, null);
    }
    
    /**************************************************************************
     * baseDeleteBy Tests
     **************************************************************************/
    
    public function test_baseDeleteBy_deletes_correct_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $gtwy->deleteByNullableVal(100);
        
        $id1 = $gtwy->retrieve($in1->getId());
        $id2 = $gtwy->retrieve($in2->getId());
        
        $this->assertEqual($id1, null);
        $this->assertEqual($id2, null);
    }

    public function test_baseDeleteBy_returns_zero_if_not_found()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $rowsAffected = $gtwy->deleteByNullableVal(200);
        $this->assertEqual($rowsAffected, 0);
        
        $gtwy->deleteByNullableVal(100);
    }
    
    /**************************************************************************
     * baseRetrieve Tests
     **************************************************************************/
    
    public function test_baseRetrieve_returns_correct_row_if_found()
    {
        $in = new GtwyTestEntity();
        $in->_name = 'John Doe';
        $in->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($in);
        
        $out = $gtwy->retrieve($in->getId());
        
        $this->assertEqual($in->getId(),       $out->getId());
        $this->assertEqual($in->getCreated(),  $out->getCreated());
        $this->assertEqual($in->getUpdated(),  $out->getUpdated());
        $this->assertEqual($in->_name,         $out->_name);
        $this->assertEqual($in->_nullable_val, intval($out->_nullable_val));
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
    
    public function test_baseRetrieve_returns_null_if_not_found()
    {
        $in = new GtwyTestEntity();
        $in->_name = 'John Doe';
        $in->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($in);
        
        $out = $gtwy->retrieve(100000000);
        
        $this->assertEqual($out, null);
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
    
    /**************************************************************************
     * baseRetrieveBy Tests
     **************************************************************************/
    
    public function test_baseRetrieveBy_retrieves_correct_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $rows = $gtwy->retrieveByNullableValue(100);
        
        $this->assertEqual(count($rows), 2);
        $this->assertEqual(intval($rows[0]->_nullable_val), 100);
        $this->assertEqual(intval($rows[1]->_nullable_val), 100);
        
        $gtwy->deleteByNullableVal(100);
    }
    
    public function test_baseRetrieveBy_returns_empty_array_if_not_found()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $rows = $gtwy->retrieveByNullableValue(200);
        
        $this->assertEqual(count($rows), 0);
        
        $gtwy->deleteByNullableVal(100);
    }

    /**************************************************************************
     * baseRetrieveBy Tests
     **************************************************************************/

    public function test_baseRetrieveByArray_retrieves_correct_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $ids = array($in1->getId(), $in2->getId());
        $results = $gtwy->retrieveByIds($ids);
        
        $this->assertEqual(count($results), 2);
        
        $gtwy->deleteByNullableVal(100);
    }
    
    public function test_baseRetrieveByArray_returns_empty_array_if_not_found()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $ids = array(1000000, 1000001);
        $results = $gtwy->retrieveByIds($ids);
        
        $this->assertEqual(count($results), 0);
        
        $gtwy->deleteByNullableVal(100);
    }
    
    /**************************************************************************
     * baseRetrieveBy Tests
     **************************************************************************/
    
    public function test_baseSetFieldNull_updates_correct_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $gtwy->setNullableValNull(100);
        
        $out1 = $gtwy->retrieve($in1->getId());
        $out2 = $gtwy->retrieve($in2->getId());
        
        $this->assertEqual($out1->_nullable_val, null);
        $this->assertEqual($out2->_nullable_val, null);
        
        $gtwy->delete($in1->getId());
        $gtwy->delete($in2->getId());
    }
    
    public function test_baseSetFieldNull_returns_number_of_affected_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $rowsAffected = $gtwy->setNullableValNull(100);
        
        $this->assertEqual($rowsAffected, 2);
        
        $gtwy->delete($in1->getId());
        $gtwy->delete($in2->getId());
    }

    public function test_baseRetrieveByIsNull_retrieves_correct_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
         
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = null;        
        $gtwy->create($in1);

        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);

        $results = $gtwy->retrieveByIsNull('nullable_val');
        $this->assertEqual(count($results), 1);

        $gtwy->delete($in1->getId());
        $gtwy->delete($in2->getId());
    }

    public function test_baseRetrieveByIsNull_returns_empty_array_if_not_found()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 100;
        $gtwy->create($in2);
        
        $results = $gtwy->retrieveByIsNull('nullable_val');
        
        $this->assertEqual(count($results), 0);
        $this->assertTrue(is_array($results));

        $gtwy->deleteByNullableVal(100);
    }

    public function test_baseRetrieveByNotEqual_retrieves_correct_rows()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
        
        $in2 = new GtwyTestEntity();
        $in2->_name = 'John Doe2';
        $in2->_nullable_val = 200;
        $gtwy->create($in2);
        
        $in3 = new GtwyTestEntity();
        $in3->_name = 'John Doe3';
        $in3->_nullable_val = 300;
        $gtwy->create($in3);
        
        $results = $gtwy->retrieveByNotEqual('nullable_val', 100);
        
        $this->assertEqual(count($results), 2);

        $gtwy->deleteByNullableVal(100);
        $gtwy->deleteByNullableVal(200);
        $gtwy->deleteByNullableVal(300);
    }
    

    public function test_baseRetrieveByNotEqual_returns_empty_array_if_not_found()
    {
        $gtwy = $this->getGatewayWithRealLogger();
        
        $in1 = new GtwyTestEntity();
        $in1->_name = 'John Doe1';
        $in1->_nullable_val = 100;
        $gtwy->create($in1);
                
        $results = $gtwy->retrieveByNotEqual('nullable_val', 100);
        
        $this->assertEqual(count($results), 0);
        $this->assertTrue(is_array($results));

        $gtwy->deleteByNullableVal(100);
    }
    

    
    /**************************************************************************
     * baseUpdate Tests
     **************************************************************************/

    public function test_baseUpdate_returns_number_of_affected_rows()
    {
        $in = new GtwyTestEntity();
        $in->_name = 'John Doe';
        $in->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($in);
        
        $in->_name = 'different name';
        $in->_nullable_val = 5000;
        
        $rowsAffected = $gtwy->update($in);
        
        $this->assertEqual($rowsAffected, 1);
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
    
    public function test_baseUpdate_properly_updates_record()
    {
        $in = new GtwyTestEntity();
        $in->_name = 'John Doe';
        $in->_nullable_val = 100;
        
        $gtwy = $this->getGatewayWithRealLogger();
        $id = $gtwy->create($in);
        
        $in->_name = 'different name';
        $in->_nullable_val = 5000;
        
        $gtwy->update($in);
        
        $out = $gtwy->retrieve($id);
        
        $this->assertEqual($out->_name, $in->_name);
        $this->assertEqual(intval($out->_nullable_val), $in->_nullable_val);
        
        // Perform clean-up.
        $gtwy->delete($id);
    }
}
