<?php
namespace tests\JoeFallon\PhpDatabase;

class ExampleEntityGatewayFactory
{
    /** @var ExampleEntityGateway */
    private static $_gateway;

    /**
     * @return ExampleEntityGateway
     */
    public static function create()
    {
        if(self::$_gateway == null)
        {
            global $pdo;
            self::$_gateway = new ExampleEntityGateway($pdo);
        }

        return self::$_gateway;
    }
}
