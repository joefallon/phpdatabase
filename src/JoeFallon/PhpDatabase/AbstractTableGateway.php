<?php
namespace JoeFallon\PhpDatabase;

use PDO;

abstract class AbstractTableGateway
{
    /** @var PDO */
    protected $_pdo;
    /** @var string */
    protected $_tableName;
    /** @var string */
    protected $_primaryKeyName;
    /** @var string */
    protected $_createdAtName;
    /** @var string */
    protected $_updatedAtName;

    /**
     * @param PDO    $db
     * @param string $tableName      Name of the table.
     * @param string $primaryKeyName Name of the primary key.
     */
    protected function __construct(PDO $db, $tableName, $primaryKeyName = 'id')
    {
        $this->_pdo            = $db;
        $this->_tableName      = $tableName;
        $this->_primaryKeyName = $primaryKeyName;
        $this->_createdAtName  = "";
        $this->_updatedAtName  = "";
    }

    /**
     * @param string $val
     */
    public function setCreatedAtName($val)
    {
        $this->_createdAtName = (string)$val;
    }

    /**
     * @param string $val
     */
    public function setUpdatedAtName($val)
    {
        $this->_updatedAtName = (string)$val;
    }

    /**
     * @param mixed $object
     *
     * @return array
     */
    abstract protected function mapObjectToArray($object);

    /**
     * @param array $array
     *
     * @return mixed
     */
    abstract protected function mapArrayToObject($array);

    /**
     * This function inserts the data, updates the created timestamp if it exists, updates
     * the updated timestamp if it exists, and returns the value of the inserted primary
     * key (e.g. 'id') on success and zero on failure.
     *
     * @param mixed $entity Entity to insert
     *
     * @return int Returns the value of the inserted primary key (e.g. 'id') on success
     *             and zero on failure.
     */
    protected function baseCreate($entity)
    {
        $pdo       = $this->_pdo;
        $tableName = $this->_tableName;
        $pkName    = $this->_primaryKeyName;

        $data = $this->mapObjectToArray($entity);
        unset($data[$pkName]);
        $data = $this->timeStampCreatedColumn($data);
        $data = $this->timeStampUpdatedColumn($data);

        $columnNames = $this->getColumnNames($data);
        $bindNames   = $this->getBindParameterNames($data);

        $sql = "INSERT INTO $tableName ( " . $columnNames . " ) VALUES ( " . $bindNames . " )";

        $statement = $pdo->prepare($sql);
        $bindParameters = $this->bindParameterData($data);
        $statement->execute($bindParameters);

        return $pdo->lastInsertId();
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
        $id = (int)$id;
        $tableName = $this->_tableName;
        $pdo = $this->_pdo;
        $sql = "SELECT * FROM $tableName WHERE id = :id LIMIT 1";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $row = $statement->fetchAll();

        if(count($row) > 0)
        {
            return $this->mapArrayToObject($row[0]);
        }

        return null;
    }


//    /**
//     * @param string $fieldName
//     * @param mixed  $fieldValue
//     *
//     * @return array
//     */
//    protected function baseRetrieveBy($fieldName, $fieldValue)
//    {
//        $fieldName = strval($fieldName);
//        $fieldVal = strval($fieldValue);
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//        $results = array();
//
//        $this->startTimer();
//
//        $sql = "SELECT * FROM $tableName WHERE $fieldName = :$fieldName";
//        $stmt = $db->prepare($sql);
//        $stmt->bindValue(':' . $fieldName, $fieldVal);
//        $stmt->execute();
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach($rows as $row)
//        {
//            $results[] = $this->convertArrayToObject($row);
//        }
//
//        $data = "fieldValue --> $fieldValue";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, count($rows), null, $data);
//
//        return $results;
//    }
//
//
//    /**
//     * @param array $ids
//     *
//     * @return array
//     */
//    protected function baseRetrieveByIds($ids)
//    {
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//        $results = array();
//
//        $this->startTimer();
//
//        $sql = "SELECT * FROM $tableName WHERE id IN ( ";
//
//        foreach($ids as $k => $v)
//        {
//            $sql .= intval($v);
//
//            if($k != (count($ids) - 1))
//            {
//                $sql .= ', ';
//            }
//        }
//
//        $sql .= ' )';
//
//        $stmt = $db->prepare($sql);
//        $stmt->execute();
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach($rows as $row)
//        {
//            $result = $this->convertArrayToObject($row);
//            $results[] = $result;
//        }
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, count($rows));
//
//        return $results;
//    }
//
//    /**
//     * @param string $fieldName
//     *
//     * @return array
//     */
//    protected function baseRetrieveByIsNull($fieldName)
//    {
//        $fieldName = strval($fieldName);
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//        $results = array();
//
//        $this->startTimer();
//
//        $sql = "SELECT * FROM $tableName WHERE $fieldName IS NULL";
//        $stmt = $db->prepare($sql);
//        $stmt->execute();
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach($rows as $row)
//        {
//            $result = $this->convertArrayToObject($row);
//            $results[] = $result;
//        }
//
//        $data = "fieldValue --> 'NULL'";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, count($rows), null, $data);
//
//        return $results;
//    }
//
//    /**
//     * @param string $fieldName
//     * @param mixed  $fieldValue
//     *
//     * @return array
//     */
//    protected function baseRetrieveByNotEqual($fieldName, $fieldValue)
//    {
//        $fieldName = strval($fieldName);
//        $fieldValue = strval($fieldValue);
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//        $results = array();
//
//        $this->startTimer();
//
//        $sql = "SELECT * FROM $tableName WHERE $fieldName != :$fieldName";
//        $stmt = $db->prepare($sql);
//        $stmt->bindValue(':' . $fieldName, $fieldValue);
//        $stmt->execute();
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach($rows as $row)
//        {
//            $result = $this->convertArrayToObject($row);
//            $results[] = $result;
//        }
//
//        $data = "fieldValue --> $fieldValue";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, count($rows), null, $data);
//
//        return $results;
//    }
//
//    /**
//     * @param AbstractEntity $entity
//     *
//     * @return int Rows Affected
//     */
//    protected function baseUpdate(AbstractEntity $entity)
//    {
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//
//        $this->startTimer();
//
//        $data = $this->convertObjectToArray($entity);
//        $data['updated'] = date('Y-m-d H:i:s');
//
//        $colNames = $this->getColumnNames($data);
//        $bindParamNames = $this->getBindParameterNames($data);
//        $bindParams = $this->convertToBindParamArray($data);
//        $count = count($colNames);
//
//        // Construct the SQL query.
//        $sql = "UPDATE " . $tableName . " SET ";
//
//        for($i = 0; $i < $count; $i++)
//        {
//            if($colNames[$i] == 'id' || $bindParamNames[$i] == ':id')
//            {
//                continue;
//            }
//
//            $sql .= $colNames[$i] . " = " . $bindParamNames[$i];
//
//            if($i < $count - 1)
//            {
//                $sql .= ", ";
//            }
//            else
//            {
//                $sql .= " ";
//            }
//        }
//
//        $sql .= ' WHERE id = :id';
//
//        $stmt = $db->prepare($sql);
//        $stmt->execute($bindParams);
//        $rowsAffected = intval($stmt->rowCount());
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, $rowsAffected, null, $data);
//
//        return $rowsAffected;
//    }
//
//
//    /**
//     * @param string $fieldName
//     * @param mixed  $fieldValue
//     *
//     * @return int Rows Affected
//     */
//    protected function baseSetFieldNull($fieldName, $fieldValue)
//    {
//        $fieldName = strval($fieldName);
//        $fieldVal = strval($fieldValue);
//        $tableName = $this->_tableName;
//        $db = $this->_db;
//
//        $this->startTimer();
//
//        $sql = 'UPDATE ' . $tableName
//            . ' SET ' . $fieldName . ' = NULL, '
//            . " updated = '" . date('Y-m-d H:i:s') . "'"
//            . ' WHERE ' . $fieldName . ' = :' . $fieldName;
//        $stmt = $db->prepare($sql);
//        $stmt->bindValue(':' . $fieldName, $fieldVal);
//        $stmt->execute();
//        $rowsAffected = $stmt->rowCount();
//
//        $data = "fieldValue --> $fieldValue";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, $rowsAffected, null, $data);
//
//        return $rowsAffected;
//    }
//
//    /**
//     * @param int $id
//     *
//     * @return int Rows affected
//     */
//    protected function baseDelete($id)
//    {
//        $id = intval($id);
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//
//        $this->startTimer();
//
//        $sql = "DELETE FROM $tableName WHERE id = :id";
//        $stmt = $db->prepare($sql);
//        $stmt->bindValue(':id', $id);
//        $stmt->execute();
//        $rowsAffected = $stmt->rowCount();
//
//        $data = "id --> $id";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, $rowsAffected, null, $data);
//
//        return $rowsAffected;
//
//    }
//
//    /**
//     * @param string $fieldName
//     * @param mixed  $fieldValue
//     *
//     * @return int
//     */
//    protected function baseDeleteBy($fieldName, $fieldValue)
//    {
//        $fieldName = strval($fieldName);
//        $fieldVal = strval($fieldValue);
//        $db = $this->_db;
//        $tableName = $this->_tableName;
//
//        $this->startTimer();
//
//        $sql = "DELETE FROM $tableName WHERE $fieldName = :$fieldName";
//
//        $stmt = $db->prepare($sql);
//        $stmt->bindValue(':' . $fieldName, $fieldVal);
//        $stmt->execute();
//        $rowsAffected = intval($stmt->rowCount());
//
//        $data = "fieldValue --> $fieldValue";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, $rowsAffected, null, $data);
//
//        return $rowsAffected;
//    }
//
//    /**
//     * @param string $fieldName
//     * @param mixed  $fieldValue
//     *
//     * @return int
//     */
//    protected function baseCountBy($fieldName, $fieldValue)
//    {
//        $fn = strval($fieldName);
//        $fv = strval($fieldValue);
//        $db = $this->_db;
//        $tn = $this->_tableName;
//        $results = array();
//
//        $this->startTimer();
//
//        $sql = "SELECT COUNT($fn) AS cnt FROM $tn WHERE $fn = :$fn";
//        $stmt = $db->prepare($sql);
//        $stmt->bindValue(':' . $fn, $fv);
//        $stmt->execute();
//        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        foreach($rows as $row)
//        {
//            $results[] = $row;
//        }
//
//        $data = "fieldValue --> $fv";
//
//        $this->stopTimer();
//        $this->logDatabaseAction($sql, count($rows), null, $data);
//
//        return intval($results[0]['cnt']);
//    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getColumnNames($data)
    {
//        $keys = array_keys($data);
//        $colNames = "";
//
//        for($i = 0; $i < count($keys); $i++)
//        {
//            $colNames .= '`';
//            $colNames .= $data[$i];
//            $colNames .= '`';
//
//            if($i < count($keys) - 1)
//            {
//                $colNames .= ', ';
//            }
//        }
//
//        return $colNames;

        $columnNames = "`";
        $keys = array_keys($data);
        $columnNames .= implode('`, `', $keys);
        $columnNames .= "`";

        return $columnNames;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function getBindParameterNames($data)
    {
        $bindNames = ":";
        $keys = array_keys($data);
        $bindNames .= implode(', :', $keys);

        return $bindNames;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function bindParameterData($data)
    {
        $bindParams = array();

        foreach($data as $key => $value)
        {
            $bindParams[':' . $key] = $value;
        }

        return $bindParams;
    }

    protected function timeStampCreatedColumn(array $data)
    {
        $createdAtColumnName = $this->_createdAtName;

        if(strlen($createdAtColumnName) > 0)
        {
            $data[$createdAtColumnName] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    protected function timeStampUpdatedColumn($data)
    {
        $updatedAtColumnName = $this->_updatedAtName;

        if(strlen($updatedAtColumnName) > 0)
        {
            $data[$updatedAtColumnName] = date('Y-m-d H:i:s');
        }

        return $data;
    }
}
