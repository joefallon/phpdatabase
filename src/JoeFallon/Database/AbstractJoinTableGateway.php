<?php
namespace JoeFallon\Database;

use Exception;
use JoeFallon\Time\Chronograph;
use JoeFallon\Time\MySqlDateTime;
use PDO;
use Psr\Log\LoggerInterface;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
abstract class AbstractJoinTableGateway
{
    /** @var PDO */
    protected $_db;
    /** @var string */
    protected $_tableName;
    /** @var string */
    protected $_id1Name;
    /** @var string */
    protected $_id2Name;
    /** @var Chronograph */
    protected $_timer;
    /** @var LoggerInterface */
    protected $_logger;


    /**
     * Class Constructor
     *
     * @param PDO             $db        Database reference.
     * @param string          $tableName Name of the table.
     * @param string          $id1Name   Name of first id column.
     * @param string          $id2Name   Name of second id column.
     * @param Chronograph     $timer     This is used for metrics.
     * @param LoggerInterface $logger    This is used for logging.
     *
     * @throws Exception
     */
    protected function __construct(PDO $db,
                                   $tableName,
                                   $id1Name,
                                   $id2Name,
                                   Chronograph $timer = null,
                                   LoggerInterface $logger = null)
    {
        if(!isset($tableName) || strlen($tableName) == 0)
        {
            throw new Exception('The table name is empty.');
        }

        if(!isset($id1Name) || strlen($id1Name) == 0)
        {
            throw new Exception('The ID1 name is empty.');
        }

        if(!isset($id2Name) || strlen($id2Name) == 0)
        {
            throw new Exception('The ID2 name is empty.');
        }

        $this->_db        = $db;
        $this->_tableName = $tableName;
        $this->_id1Name   = $id1Name;
        $this->_id2Name   = $id2Name;
        $this->_timer     = $timer;
        $this->_logger    = $logger;
    }


    /**
     * @param int $id1 ID of first column.
     * @param int $id2 ID of second column.
     *
     * @return int
     */
    protected function baseDelete($id1, $id2)
    {
        $id1       = intval($id1);
        $id2       = intval($id2);
        $tableName = $this->_tableName;
        $id1Name   = $this->_id1Name;
        $id2Name   = $this->_id2Name;
        $db        = $this->_db;

        $this->startTimer();

        $sql  = "DELETE FROM $tableName WHERE $id1Name = $id1 AND $id2Name = $id2";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rowsAffectedCount = $stmt->rowCount();

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffectedCount);

        return $rowsAffectedCount;
    }


    /**
     * Start the timer.
     */
    protected function startTimer()
    {
        if($this->_timer != null)
        {
            $this->_timer->start();
        }
    }


    /**
     * Sop the timer.
     */
    protected function stopTimer()
    {
        if($this->_timer != null)
        {
            $this->_timer->stop();
        }
    }


    /**
     * @param string  $sql SQL to log.
     * @param integer $affectedRowsCount
     */
    protected function logDatabaseAction($sql, $affectedRowsCount)
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
    }


    /**
     * @param int $id1  ID of the first column
     * @param int $id2  ID of the second column
     *
     * @return int
     */
    protected function baseCreate($id1, $id2)
    {
        $id1       = intval($id1);
        $id2       = intval($id2);
        $tableName = $this->_tableName;
        $id1Name   = $this->_id1Name;
        $id2Name   = $this->_id2Name;
        $db        = $this->_db;
        $created   = MySqlDateTime::nowTimestamp();
        $this->startTimer();

        $sql  = "INSERT INTO $tableName ( $id1Name, $id2Name, created ) "
                . "VALUES ( $id1, $id2, '$created' )";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rowsAffectedCount = $stmt->rowCount();

        $this->stopTimer();
        $this->logDatabaseAction($sql, $rowsAffectedCount);

        return $rowsAffectedCount;
    }


    /**
     * @param string $colName  The column name to search
     * @param int    $id       The ID to search for
     *
     * @return array
     */
    protected function baseRetrieveById($colName, $id)
    {
        $id        = intval($id);
        $tableName = $this->_tableName;
        $db        = $this->_db;
        $this->startTimer();

        $sql  = "SELECT * FROM $tableName WHERE $colName = $id";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->stopTimer();
        $this->logDatabaseAction($sql, count($rows));

        return $rows;
    }


    /**
     * baseDeleteById
     *
     * @param string $colName  The column name to search
     * @param int    $id       The ID to search for
     *
     * @return int
     */
    protected function baseDeleteById($colName, $id)
    {
        $id        = intval($id);
        $tableName = $this->_tableName;
        $db        = $this->_db;
        $this->startTimer();

        $sql = "DELETE FROM $tableName WHERE $colName = $id";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $affectedRowsCount = intval($stmt->rowCount());

        $this->stopTimer();
        $this->logDatabaseAction($sql, $affectedRowsCount);

        return $affectedRowsCount;
    }
}