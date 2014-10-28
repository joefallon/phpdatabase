<?php
namespace JoeFallon\PhpDatabase;

use Exception;
use PDO;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
class PdoFactory
{
    /**
     * @param string $host Database host.
     * @param string $port Database port.
     * @param string $user Database username.
     * @param string $pass Database password.
     * @param string $name Database name.
     *
     * @return PDO
     * @throws \Exception
     */
    public static function create($host, $port, $user, $pass, $name)
    {
        $dbHost = strval($host);
        $dbPort = strval($port);
        $dbUser = strval($user);
        $dbPass = strval($pass);
        $dbName = strval($name);

        if(strlen($dbName) == 0)
        {
            $msg = 'DB name is empty.';
            throw new Exception($msg);
        }
        else if(strlen($dbHost) == 0)
        {
            $msg = 'DB host is empty.';
            throw new Exception($msg);
        }
        else if(strlen($dbPort) == 0)
        {
            $msg = 'DB port is empty.';
            throw new Exception($msg);
        }
        else if(strlen($dbUser) == 0)
        {
            $msg = 'DB user is empty.';
            throw new Exception($msg);
        }
        else if(strlen($dbPass) == 0)
        {
            $msg = 'DB pass is empty.';
            throw new Exception($msg);
        }

        $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName";
        $db  = new PDO($dsn, $dbUser, $dbPass);
        $db->setAttribute(PDO::ATTR_ERRMODE,             PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,  PDO::FETCH_ASSOC);
        $db->setAttribute(PDO::ATTR_EMULATE_PREPARES,    false);

        return $db;
    }
}