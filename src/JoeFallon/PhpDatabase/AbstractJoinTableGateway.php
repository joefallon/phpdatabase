<?php
namespace JoeFallon\PhpDatabase;

use PDO;

abstract class AbstractJoinTableGateway
{
    /** @var PDO */
    protected $_pdo;
    /** @var string */
    protected $_tableName;
    /** @var string */
    protected $_id1Name;
    /** @var string */
    protected $_id2Name;
    /** @var string */
    protected $_createdAtName;

    /**
     * @param PDO    $pdo           Database reference.
     * @param string $tableName     Name of the table.
     * @param string $id1Name       Name of first id column.
     * @param string $id2Name       Name of second id column.
     * @param string $createdAtName Name of created field (optional).
     */
    protected function __construct(PDO $pdo, $tableName, $id1Name, $id2Name, $createdAtName="")
    {
        $this->_pdo           = $pdo;
        $this->_tableName     = $tableName;
        $this->_id1Name       = $id1Name;
        $this->_id2Name       = $id2Name;
        $this->_createdAtName = $createdAtName;
    }

    /**
     * @param int $id1 ID of the first column
     * @param int $id2 ID of the second column
     *
     * @return int Rows affected.
     */
    protected function baseCreate($id1, $id2)
    {
        $id1     = (int)$id1;
        $id2     = (int)$id2;
        $table   = $this->_tableName;
        $id1Name = $this->_id1Name;
        $id2Name = $this->_id2Name;
        $created = $this->_createdAtName;
        $pdo     = $this->_pdo;

        $columnNamesArr = ["`$id1Name`", "`$id2Name`"];
        $parametersArr  = [$id1, $id2];

        if(strlen($this->_createdAtName) > 0)
        {
            $columnNamesArr[] = "`$created`";
            $now = date('Y-m-d H:i:s');
            $parametersArr[]  = "'$now'";
        }

        $columnNamesStr = implode(', ', $columnNamesArr);
        $parametersStr  = implode(', ', $parametersArr);

        $sql  = "INSERT INTO $table ( $columnNamesStr ) VALUES ( $parametersStr )";
        $statement = $pdo->prepare($sql);
        $statement->execute();

        return $statement->rowCount();
    }


    /**
     * @param int $id1
     * @param int $id2
     *
     * @return array
     */
    protected function baseRetrieve($id1, $id2)
    {
        $id1     = (int)$id1;
        $id2     = (int)$id2;
        $table   = $this->_tableName;
        $id1Name = $this->_id1Name;
        $id2Name = $this->_id2Name;
        $pdo     = $this->_pdo;

        $sql = "SELECT * FROM $table WHERE `$id1Name`=$id1 AND `$id2Name`=$id2 LIMIT 1";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(count($rows) == 0)
        {
            return array();
        }

        return $rows[0];
    }

    /**
     * @param int $id1 Id of first column.
     * @param int $id2 Id of second column.
     *
     * @return int Rows affected.
     */
    protected function baseDelete($id1, $id2)
    {
        $id1     = (int)$id1;
        $id2     = (int)$id2;
        $table   = $this->_tableName;
        $id1Name = $this->_id1Name;
        $id2Name = $this->_id2Name;
        $pdo     = $this->_pdo;

        $sql  = "DELETE FROM $table WHERE `$id1Name`=$id1 AND `$id2Name`=$id2";
        $statement = $pdo->prepare($sql);
        $statement->execute();

        return $statement->rowCount();
    }


    /**
     * @param string $columnName The column name to search
     * @param int    $id         The ID to search for
     *
     * @return array
     */
    protected function baseRetrieveById($columnName, $id)
    {
        $id    = (int)$id;
        $table = $this->_tableName;
        $pdo   = $this->_pdo;

        $sql  = "SELECT * FROM $table WHERE `$columnName`=$id";

        $statement = $pdo->prepare($sql);
        $statement->execute();
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    /**
     * baseDeleteById
     *
     * @param string $columnName The column name to search
     * @param int    $id         The ID to search for
     *
     * @return int Rows affected.
     */
    protected function baseDeleteById($columnName, $id)
    {
        $id    = (int)$id;
        $table = $this->_tableName;
        $pdo   = $this->_pdo;

        $sql = "DELETE FROM $table WHERE `$columnName`=$id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        return $stmt->rowCount();
    }
}
