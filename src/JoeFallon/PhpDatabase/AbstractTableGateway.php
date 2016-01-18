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
     * @param string $primaryKeyName Name of the integer primary key column.
     */
    protected function __construct(PDO $db, $tableName, $primaryKeyName = 'id')
    {
        $this->_pdo = $db;
        $this->_tableName = $tableName;
        $this->_primaryKeyName = $primaryKeyName;
        $this->_createdAtName = "";
        $this->_updatedAtName = "";
    }

    /**
     * @param mixed $object
     *
     * @return array
     */
    abstract protected function mapObjectToArray($object);

    /**
     * @param array $arr
     *
     * @return mixed
     */
    abstract protected function mapArrayToObject($arr);

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
     * @param $entity
     *
     * @return int
     */
    protected function baseCreate($entity)
    {
        $pdo    = $this->_pdo;
        $table  = $this->_tableName;
        $pkName = $this->_primaryKeyName;

        $data = $this->mapObjectToArray($entity);
        $data = $this->timeStampCreatedColumn($data);
        $data = $this->timeStampUpdatedColumn($data);
        unset($data[$pkName]);

        $columnNamesArr = $this->getQuotedColumnNames($data);
        $columnNamesStr = implode(', ', $columnNamesArr);
        $paramNamesArr  = $this->getParameterNames($data);
        $paramNamesStr  = implode(', ', $paramNamesArr);

        $sql = "INSERT INTO $table ( " . $columnNamesStr . " ) VALUES ( " . $paramNamesStr . " )";

        $statement = $pdo->prepare($sql);
        $bindParameters = $this->bindParameterData($data);
        $statement->execute($bindParameters);

        return (int)$pdo->lastInsertId();
    }

    /**
     * This function updates the "created at" timestamp with the current time if the name
     * for the "created at" timestamp column has been previously specified.
     *
     * @param array $data
     *
     * @return array
     */
    protected function timeStampCreatedColumn(array $data)
    {
        $createdAtColumnName = $this->_createdAtName;

        if(strlen($createdAtColumnName) > 0)
        {
            $data[$createdAtColumnName] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    /**
     * This function updates the "updated at" timestamp with the current time if the name
     * for the "updated at" timestamp column has been previously specified.
     *
     * @param $data
     *
     * @return mixed
     */
    protected function timeStampUpdatedColumn($data)
    {
        $updatedAtColumnName = $this->_updatedAtName;

        if(strlen($updatedAtColumnName) > 0)
        {
            $data[$updatedAtColumnName] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    /**
     * Example: ["`colName1`", "`colName2`"]
     *
     * @param $data
     *
     * @return array
     */
    protected function getQuotedColumnNames($data)
    {
        $keys    = array_keys($data);
        $results = [];

        foreach($keys as $k)
        {
            $results[] = "`$k`";
        }

        return $results;
    }

    /**
     * Example: [":colName1", ":colName2"]
     *
     * @param $data
     *
     * @return array
     */
    protected function getParameterNames($data)
    {
        $keys = array_keys($data);
        $results = [];

        foreach($keys as $k)
        {
            $results[] = ":$k";
        }

        return $results;
    }

    /**
     * Example Result:
     * [
     *      ":col1" => "column 1 value",
     *      ":col2" => "column 2 value",
     * ]
     *
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

    /**
     * @param $primaryKey
     *
     * @return mixed|null
     */
    protected function baseRetrieve($primaryKey)
    {
        $primaryKey     = (int)$primaryKey;
        $primaryKeyName = $this->_primaryKeyName;
        $tableName      = $this->_tableName;
        $pdo            = $this->_pdo;

        $sql = "SELECT * FROM $tableName WHERE `$primaryKeyName`=$primaryKey LIMIT 1";

        $statement = $pdo->prepare($sql);
        $statement->execute();
        $row = $statement->fetchAll();

        if(count($row) == 0)
        {
            return null;
        }

        return $this->mapArrayToObject($row[0]);
    }

    /**
     * @param mixed $entity
     *
     * @return int
     */
    protected function baseUpdate($entity)
    {
        $pdo    = $this->_pdo;
        $data   = $this->mapObjectToArray($entity);
        $data   = $this->timeStampUpdatedColumn($data);
        $table  = $this->_tableName;
        $pkName = $this->_primaryKeyName;
        $pk     = intval($data[$pkName]);

        $parametersArray  = $this->getParameterizedColumnNames($data);
        $parametersString = implode(', ', $parametersArray);

        $sql = "UPDATE $table SET $parametersString WHERE `$pkName`=$pk";

        $statement      = $pdo->prepare($sql);
        $bindParameters = $this->bindParameterData($data);
        $statement->execute($bindParameters);

        return $statement->rowCount();
    }

    /**
     * Example Result: ["`col1`=:col1", "`col2`=:col2"]
     *
     * @param $data
     *
     * @return array
     */
    protected function getParameterizedColumnNames($data)
    {
        $keys = array_keys($data);
        $result = [];

        foreach($keys as $k)
        {
            $result[] = "`$k`=:$k";
        }

        return $result;
    }

    /**
     * @param int $primaryKey
     *
     * @return int Rows affected
     */
    protected function baseDelete($primaryKey)
    {
        $primaryKey = (int)$primaryKey;
        $pdo    = $this->_pdo;
        $table  = $this->_tableName;
        $pkName = $this->_primaryKeyName;

        $sql = "DELETE FROM $table WHERE `$pkName`=$primaryKey";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":$pkName", $primaryKey);
        $stmt->execute();

        return $stmt->rowCount();
    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return array
     */
    protected function baseRetrieveBy($fieldName, $fieldValue)
    {
        $fieldName  = (string)$fieldName;
        $fieldValue = (string)$fieldValue;

        $pdo   = $this->_pdo;
        $table = $this->_tableName;

        $sql = "SELECT * FROM $table WHERE `$fieldName`=:$fieldName";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$fieldName", $fieldValue);
        $statement->execute();

        $results = [];

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $results[] = $this->mapArrayToObject($row);
        }

        return $results;
    }

    /**
     * @param array $primaryKeysArray
     *
     * @return array
     */
    protected function baseRetrieveByIds(array $primaryKeysArray)
    {
        $pdo     = $this->_pdo;
        $table   = $this->_tableName;
        $keyList = implode(', ', $primaryKeysArray);

        $sql = "SELECT * FROM $table WHERE id IN ( $keyList )";

        $statement = $pdo->prepare($sql);
        $statement->execute();

        $results = [];

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $results[] = $this->mapArrayToObject($row);
        }

        return $results;
    }

    /**
     * @param string $fieldName
     *
     * @return array
     */
    protected function baseRetrieveByIsNull($fieldName)
    {
        $fieldName = (string)$fieldName;
        $pdo       = $this->_pdo;
        $table     = $this->_tableName;

        $sql = "SELECT * FROM $table WHERE `$fieldName` IS NULL";
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $results = [];

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $results[] = $this->mapArrayToObject($row);
        }

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
        $fieldName  = (string)$fieldName;
        $fieldValue = (string)$fieldValue;

        $pdo   = $this->_pdo;
        $table = $this->_tableName;

        $sql = "SELECT * FROM $table WHERE `$fieldName` != :$fieldName";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$fieldName", $fieldValue);
        $statement->execute();

        $results = [];

        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
            $results[] = $this->mapArrayToObject($row);
        }

        return $results;
    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return int Rows affected
     */
    protected function baseSetFieldNull($fieldName, $fieldValue)
    {
        $fieldName = (string)$fieldName;
        $fieldVal = (string)$fieldValue;

        $table = $this->_tableName;
        $pdo   = $this->_pdo;

        if(strlen($this->_updatedAtName) > 0)
        {
            $updated = $this->_updatedAtName;
            $now     = date('Y-m-d H:i:s');
            $params  = "`$fieldName`=NULL, `$updated`='$now'";
        }
        else
        {
            $params = "`$fieldName`=NULL";
        }

        $sql = "UPDATE $table SET $params WHERE `$fieldName`=:$fieldName";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(':' . $fieldName, $fieldVal);
        $statement->execute();

        return $statement->rowCount();
    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return int Rows affected
     */
    protected function baseDeleteBy($fieldName, $fieldValue)
    {
        $fieldName = (string)$fieldName;
        $fieldVal  = (string)$fieldValue;

        $pdo   = $this->_pdo;
        $table = $this->_tableName;

        $sql = "DELETE FROM $table WHERE `$fieldName`=:$fieldName";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$fieldName", $fieldVal);
        $statement->execute();

        return $statement->rowCount();
    }

    /**
     * @param string $fieldName
     * @param mixed  $fieldValue
     *
     * @return int Row count
     */
    protected function baseCountBy($fieldName, $fieldValue)
    {
        $fieldName  = (string)$fieldName;
        $fieldValue = (string)$fieldValue;

        $pdo   = $this->_pdo;
        $table = $this->_tableName;

        $sql = "SELECT COUNT($fieldName) AS val_count FROM $table WHERE `$fieldName`=:$fieldName";

        $statement = $pdo->prepare($sql);
        $statement->bindValue(":$fieldName", $fieldValue);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return intval($rows[0]['val_count']);
    }
}
