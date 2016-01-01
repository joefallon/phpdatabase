<?php
namespace tests\JoeFallon\PhpDatabase;

class ExampleJoinEntity
{
    private $_id1;
    private $_id2;
    private $_created;

    public function __construct()
    {
        $this->_id1 = 0;
        $this->_id2 = 0;
        $this->_created = '';
    }

    /**
     * @return int
     */
    public function getId1()
    {
        return $this->_id1;
    }

    /**
     * @param int $id1
     */
    public function setId1($id1)
    {
        $this->_id1 = (int)$id1;
    }

    /**
     * @return int
     */
    public function getId2()
    {
        return $this->_id2;
    }

    /**
     * @param int $id2
     */
    public function setId2($id2)
    {
        $this->_id2 = (int)$id2;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->_created;
    }

    /**
     * @param string $created
     */
    public function setCreated($created)
    {
        $this->_created = (string)$created;
    }


}
