<?php
namespace JoeFallon\PhpDatabase;

use Exception;
use PDO;

class PdoFactory
{
    /**
     * @param string $dbName Database name
     * @param string $dbUser Username
     * @param string $dbPass Password
     * @param string $dbHost Database host
     * @param string $dbPort Database port
     *
     * @return PDO
     * @throws Exception
     */
    public static function create($dbName, $dbUser, $dbPass,
                                  $dbHost = 'localhost', $dbPort = '3306')
    {
        $dbName = strval($dbName);
        $dbUser = strval($dbUser);
        $dbPass = strval($dbPass);
        $dbHost = strval($dbHost);
        $dbPort = strval($dbPort);

        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
        $db = new PDO($dsn, $dbUser, $dbPass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        return $db;
    }
}
