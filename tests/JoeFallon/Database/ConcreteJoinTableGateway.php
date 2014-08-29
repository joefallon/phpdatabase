<?php
namespace tests\JoeFallon\Database;

use JoeFallon\Database\AbstractJoinTableGateway;
use JoeFallon\Time\Chronograph;
use PDO;
use Psr\Log\LoggerInterface;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
class ConcreteJoinTableGateway extends AbstractJoinTableGateway
{
    public function __construct(PDO $db,
                                $tableName,
                                $id1Name,
                                $id2Name,
                                Chronograph $timer,
                                LoggerInterface $logger)
    {
        parent::__construct($db, $tableName, $id1Name, $id2Name, $timer, $logger);
    }


    public function create($id1, $id2)
    {
        return $this->baseCreate($id1, $id2);
    }


    public function delete($id1, $id2)
    {
        return $this->baseDelete($id1, $id2);
    }


    public function retrieveById1($id1)
    {
        return $this->baseRetrieveById('id1', $id1);
    }


    public function deleteById1($id1)
    {
        return $this->baseDeleteById('id1', $id1);
    }
}