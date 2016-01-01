<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\KissTest\UnitTest;

class AbstractJoinTableGatewayTests extends UnitTest
{



//    public function test_empty_tableName_throws_exception()
//    {
//        global $pdo;
//
//        $path   = realpath(BASE_PATH . '/tests/logs') . '/' . date('Y-m-d') . '.log';
//        $logger = new Log($path, Log::DEBUG);
//
//        try
//        {
//            $gtwy = new ConcreteJoinTableGateway($pdo, '', 'id1', 'id2', $logger);
//        }
//        catch(Exception $e)
//        {
//            $this->testPass();
//
//            return;
//        }
//
//        $this->testFail();
//    }
//
//
//    public function test_empty_id1Name_throws_exception()
//    {
//        global $pdo;
//
//        $path   = realpath(BASE_PATH . '/tests/logs') . '/' . date('Y-m-d') . '.log';
//        $logger = new Log($path, Log::DEBUG);
//
//        try
//        {
//            $gtwy = new ConcreteJoinTableGateway($pdo, 'join_tests',
//                                                 '', 'id2', $logger);
//        }
//        catch(Exception $e)
//        {
//            $this->testPass();
//
//            return;
//        }
//
//        $this->testFail();
//    }
//
//
//    public function test_empty_id2Name_throws_exception()
//    {
//        global $pdo;
//
//        $path   = realpath(BASE_PATH . '/tests/logs') . '/' . date('Y-m-d') . '.log';
//        $logger = new Log($path, Log::DEBUG);
//
//        try
//        {
//            $gtwy = new ConcreteJoinTableGateway($pdo, 'join_tests',
//                                                 'id1', '', $logger);
//        }
//        catch(Exception $e)
//        {
//            $this->testPass();
//
//            return;
//        }
//
//        $this->testFail();
//    }
//
//
//    public function test_creation_and_retrieval()
//    {
//        $id1 = 4;
//        $id2 = 5;
//
//        $gtwy         = $this->getGatewayWithRealLogger();
//        $rowsAffected = $gtwy->create($id1, $id2);
//
//        $this->assertEqual($rowsAffected, 1);
//
//        $rows = $gtwy->retrieveById1($id1);
//
//        $this->assertEqual(count($rows), 1);
//
//        $row1 = $rows[0];
//
//        $this->assertEqual($row1['id1'], '4');
//        $this->assertEqual($row1['id2'], '5');
//
//        $rowsAffected = $gtwy->delete($id1, $id2);
//
//        $this->assertEqual($rowsAffected, 1);
//
//        $rows = $gtwy->retrieveById1($id1);
//        $this->assertEqual(count($rows), 0);
//
//        $rowsAffected = $gtwy->create($id1, $id2);
//        $this->assertEqual($rowsAffected, 1);
//
//        $rowsAffected = $gtwy->deleteById1($id1);
//        $this->assertEqual($rowsAffected, 1);
//
//        $rows = $gtwy->retrieveById1($id1);
//        $this->assertEqual(count($rows), 0);
//    }
//
//
//    /**
//     * getGatewayWithRealLogger
//     *
//     * @return ConcreteJoinTableGateway
//     */
//    private function getGatewayWithRealLogger()
//    {
//        global $pdo;
//
//        $path   = realpath(BASE_PATH . '/tests/logs') . '/' . date('Y-m-d') . '.log';
//        $logger = new Log($path, Log::DEBUG);
//        $timer  = new Chronograph();
//        $gtwy   = new ConcreteJoinTableGateway($pdo, 'join_tests', 'id1', 'id2', $logger);
//
//        return $gtwy;
//    }
}
