<?php
namespace JoeFallon\PhpDatabase;

use InvalidArgumentException;
use JoeFallon\PhpTime\Chronograph;
use PDO;
use Psr\Log\LoggerInterface;
use stdClass;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
abstract class AbstractTableGateway
{
    /** @var PDO */
    protected $_db;
    /** @var string */
    protected $_tableName;
    /** @var Chronograph */
    protected $_timer;
    /** @var LoggerInterface */
    protected $_logger;


    /**
     * @param PDO             $db
     * @param string          $tableName Name of the table.
     * @param LoggerInterface $logger    This is used for logging.
     */
    protected function __construct(PDO $db, $tableName, LoggerInterface $logger = null)
    {
        $this->_db        = $db;
        $this->_tableName = $tableName;
        $this->_timer     = new Chronograph();
        $this->_logger    = $logger;
    }

    /**
     * @param mixed $object
     *
     * @return array
     */
    abstract protected function convertObjectToArray($object);

    /**
     * @param array $array
     *
     * @return mixed
     */
    abstract protected function convertArrayToObject($array);

    /**
     * This function inserts the data, updates the id, created and
     * updated timestamps, and returns the inserted id on success
     * zero on failure.
     *
     * @param AbstractEntity $entity
     *
     * @return int
     * @throws InvalidArgumentException
     */
    protected function baseCreate(AbstractEntity $entity)
    {
        $tableName = $this->_tableName;
        $db        = $this->_db;

        $this->startTimer();

        if($entity->isValid() == false)
        {
            $msg = 'Entity is invalid.';
            throw new InvalidArgumentException($msg);
        }

        $data = $this->convertObjectToArray($entity);

        $data['created'] = date('Y-m-d H:i:s');
        $data['updated'] = date('Y-m-d H:i:s');

        $colNames       = $this->getColumnNames($data);
        $bindParamNames = $this->getBindParameterNames($data);
        $bindParams     = $this->convertToBindParamArray($data);

        $sql = "INSERT INTO $tableName  ( " . implode(", ", $colNames) . " ) "
               . "VALUES ( " . implode(", ", $bindParamNames) . " )";

        $stmt = $db->prepare($sql);
        $stmt->execute($bindParams);
        $insertedId = intval($db->lastInsertId());
        $data['id'] = $insertedId;

        $entity->setId($insertedId);
        $entity->setCreated($data['created']);
        $entity->setUpdated($data['updated']);
        $rowsAffected = $insertedId > 0 ? 1 : 0;

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffected, $insertedId, $data);

        return $insertedId;
    }

    /**
     * This function retrieves the object from the database
     * specified by the $id. This method assumes the primary key of the
     * table is named `id`.
     *
     * @param integer $id Id of the object to return.
     *
     * @return mixed  The retrieved row, converted to an object.
     */
    protected function baseRetrieve($id)
    {
        $id        = intval($id);
        $tableName = $this->_tableName;
        $db        = $this->_db;
        $result    = null;

        $this->startTimer();

        $sql  = "SELECT * FROM $tableName WHERE id = :id LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $row    = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $rowCnt = count($row);

        if($rowCnt > 0)
        {
            /* @var $result AbstractEntity */
            $result = $this->convertArrayToObject($row[0]);
        }

        $data = count($row) > 0 ? $row[0] : "id --> $id";

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowCnt, null, $data);

        return $result;
    }


    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return array
     */
    protected function baseRetrieveBy($fieldName, $fieldValue)
    {
        $fieldName = strval($fieldName);
        $fieldVal  = strval($fieldValue);
        $db        = $this->_db;
        $tableName = $this->_tableName;
        $results   = array();

        $this->startTimer();

        $sql  = "SELECT * FROM $tableName WHERE $fieldName = :$fieldName";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':' . $fieldName, $fieldVal);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row)
        {
            $results[] = $this->convertArrayToObject($row);
        }

        $data = "fieldValue --> $fieldValue";

        $this->stopTimer();
        $this->logDatabaseAction($sql, count($rows), null, $data);

        return $results;
    }


    /**
     * @param array $ids
     *
     * @return array
     */
    protected function baseRetrieveByIds($ids)
    {
        $db        = $this->_db;
        $tableName = $this->_tableName;
        $results   = array();

        $this->startTimer();

        $sql = "SELECT * FROM $tableName WHERE id IN ( ";

        foreach($ids as $k => $v)
        {
            $sql .= intval($v);

            if($k != (count($ids) - 1))
            {
                $sql .= ', ';
            }
        }

        $sql .= ' )';

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row)
        {
            $result    = $this->convertArrayToObject($row);
            $results[] = $result;
        }

        $this->stopTimer();
        $this->logDatabaseAction($sql, count($rows));

        return $results;
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    protected function baseRetrieveByIsNull($fieldName)
    {
        $fieldName = strval($fieldName);
        $db        = $this->_db;
        $tableName = $this->_tableName;
        $results   = array();

        $this->startTimer();

        $sql  = "SELECT * FROM $tableName WHERE $fieldName IS NULL";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row)
        {
            $result    = $this->convertArrayToObject($row);
            $results[] = $result;
        }

        $data = "fieldValue --> 'NULL'";

        $this->stopTimer();
        $this->logDatabaseAction($sql, count($rows), null, $data);

        return $results;
    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return array
     */
    protected function baseRetrieveByNotEqual($fieldName, $fieldValue)
    {
        $fieldName  = strval($fieldName);
        $fieldValue = strval($fieldValue);
        $db         = $this->_db;
        $tableName  = $this->_tableName;
        $results    = array();

        $this->startTimer();

        $sql  = "SELECT * FROM $tableName WHERE $fieldName != :$fieldName";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':' . $fieldName, $fieldValue);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row)
        {
            $result    = $this->convertArrayToObject($row);
            $results[] = $result;
        }

        $data = "fieldValue --> $fieldValue";

        $this->stopTimer();
        $this->logDatabaseAction($sql, count($rows), null, $data);

        return $results;
    }

    /**
     * @param AbstractEntity $entity
     *
     * @return int Rows Affected
     */
    protected function baseUpdate(AbstractEntity $entity)
    {
        $db        = $this->_db;
        $tableName = $this->_tableName;

        $this->startTimer();

        $data            = $this->convertObjectToArray($entity);
        $data['updated'] = date('Y-m-d H:i:s');

        $colNames       = $this->getColumnNames($data);
        $bindParamNames = $this->getBindParameterNames($data);
        $bindParams     = $this->convertToBindParamArray($data);
        $count          = count($colNames);

        // Construct the SQL query.
        $sql = "UPDATE " . $tableName . " SET ";

        for($i = 0; $i < $count; $i++)
        {
            if($colNames[$i] == 'id' || $bindParamNames[$i] == ':id')
            {
                continue;
            }

            $sql .= $colNames[$i] . " = " . $bindParamNames[$i];

            if($i < $count - 1)
            {
                $sql .= ", ";
            }
            else
            {
                $sql .= " ";
            }
        }

        $sql .= ' WHERE id = :id';

        $stmt = $db->prepare($sql);
        $stmt->execute($bindParams);
        $rowsAffected = intval($stmt->rowCount());

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffected, null, $data);

        return $rowsAffected;
    }


    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return int Rows Affected
     */
    protected function baseSetFieldNull($fieldName, $fieldValue)
    {
        $fieldName = strval($fieldName);
        $fieldVal  = strval($fieldValue);
        $tableName = $this->_tableName;
        $db        = $this->_db;

        $this->startTimer();

        $sql  = 'UPDATE ' . $tableName
                . ' SET ' . $fieldName . ' = NULL, '
                . " updated = '" . date('Y-m-d H:i:s') . "'"
                . ' WHERE ' . $fieldName . ' = :' . $fieldName;
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':' . $fieldName, $fieldVal);
        $stmt->execute();
        $rowsAffected = $stmt->rowCount();

        $data = "fieldValue --> $fieldValue";

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffected, null, $data);

        return $rowsAffected;
    }

    /**
     * @param int $id
     *
     * @return int Rows affected
     */
    protected function baseDelete($id)
    {
        $id        = intval($id);
        $db        = $this->_db;
        $tableName = $this->_tableName;

        $this->startTimer();

        $sql  = "DELETE FROM $tableName WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $rowsAffected = $stmt->rowCount();

        $data = "id --> $id";

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffected, null, $data);

        return $rowsAffected;

    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return int
     */
    protected function baseDeleteBy($fieldName, $fieldValue)
    {
        $fieldName = strval($fieldName);
        $fieldVal  = strval($fieldValue);
        $db        = $this->_db;
        $tableName = $this->_tableName;

        $this->startTimer();

        $sql = "DELETE FROM $tableName WHERE $fieldName = :$fieldName";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':' . $fieldName, $fieldVal);
        $stmt->execute();
        $rowsAffected = intval($stmt->rowCount());

        $data = "fieldValue --> $fieldValue";

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffected, null, $data);

        return $rowsAffected;
    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return int
     */
    protected function baseCountBy($fieldName, $fieldValue)
    {
        $fn      = strval($fieldName);
        $fv      = strval($fieldValue);
        $db      = $this->_db;
        $tn      = $this->_tableName;
        $results = array();

        $this->startTimer();

        $sql  = "SELECT COUNT($fn) AS cnt FROM $tn WHERE $fn = :$fn";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':' . $fn, $fv);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row)
        {
            $results[] = $row;
        }

        $data = "fieldValue --> $fv";

        $this->stopTimer();
        $this->logDatabaseAction($sql, count($rows), null, $data);

        return intval($results[0]['cnt']);
    }

    protected function startTimer()
    {
        if($this->_timer != null)
        {
            $this->_timer->start();
        }
    }

    protected function stopTimer()
    {
        if($this->_timer != null)
        {
            $this->_timer->stop();
        }
    }

    /**
     * @param string $sql
     * @param int    $affectedRowsCount
     * @param null   $insertId
     * @param null   $data
     */
    protected function logDatabaseAction( $sql,
                                          $affectedRowsCount,
                                          $insertId = null,
                                          $data = null )
    {
        $logger = $this->_logger;
        if($logger == null)
        {
            return;
        }

        $t = $this->_timer->getElapsedTimeInMillisecs() . 'mS';

        $logger->info('----------');
        $logger->info(get_class($this));
        $logger->info('sql = ' . $sql);
        $logger->info('Rows Affected = ' . $affectedRowsCount);
        $logger->info('Execution Time = ' . $t);

        if($insertId > 0)
        {
            $logger->info('Insert Id = ' . $insertId);
        }

        if($data != null)
        {
            $logger->info('data = ' . print_r($data, true));
        }
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getColumnNames($data)
    {
        $colNames = array();

        foreach($data as $key => $value)
        {
            $colNames[] = $key;
        }

        return $colNames;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getBindParameterNames($data)
    {
        $bindParamNames = array();

        foreach($data as $key => $value)
        {
            $bindParamNames[] = ':' . $key;
        }

        return $bindParamNames;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function convertToBindParamArray($data)
    {
        $bindParams = array();

        foreach($data as $key => $value)
        {
            $bindParams[':' . $key] = $value;
        }

        return $bindParams;
    }
}
