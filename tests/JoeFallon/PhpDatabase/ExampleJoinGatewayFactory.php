<?php
namespace tests\JoeFallon\PhpDatabase;

class ExampleJoinGatewayFactory
{
    /** @var ExampleJoinGateway */
    private static $_gateway;

    /**
     * @return ExampleJoinGateway
     */
    public static function create()
    {
        if(self::$_gateway == null)
        {
            global $pdo;
            self::$_gateway = new ExampleJoinGateway($pdo);
        }

        return self::$_gateway;
    }
}
