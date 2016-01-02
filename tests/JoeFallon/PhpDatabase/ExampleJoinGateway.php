<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\PhpDatabase\AbstractJoinTableGateway;
use PDO;

class ExampleJoinGateway extends AbstractJoinTableGateway
{
    const ID1_NAME = 'id1';

    /**
     * @param PDO $pdo
     */
    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo, 'example_join_table', self::ID1_NAME, 'id2', 'created');
    }

    /**
     * @param int $id1
     * @param int $id2
     *
     * @return int
     */
    public function create($id1, $id2)
    {
        return $this->baseCreate($id1, $id2);
    }

    /**
     * @param int $id1
     * @param int $id2
     *
     * @return array
     */
    public function retrieve($id1, $id2)
    {
        return $this->baseRetrieve($id1, $id2);
    }


    /**
     * @param int $id1
     * @param int $id2
     *
     * @return int
     */
    public function delete($id1, $id2)
    {
        return $this->baseDelete($id1, $id2);
    }

    /**
     * @param int $id1
     *
     * @return array
     */
    public function retrieveById1($id1)
    {
        return $this->baseRetrieveById(self::ID1_NAME, $id1);
    }

    /**
     * @param int $id1
     *
     * @return int Rows affected
     */
    public function deleteById1($id1)
    {
        return $this->baseDeleteById(self::ID1_NAME, $id1);
    }
}
