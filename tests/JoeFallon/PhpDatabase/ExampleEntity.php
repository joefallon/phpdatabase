<?php
namespace tests\JoeFallon\PhpDatabase;

class ExampleEntity
{
    private $_id;
    private $_name;
    private $_nullable;
    private $_created;
    private $_updated;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getNullable()
    {
        return $this->_nullable;
    }

    /**
     * @param string $nullable
     */
    public function setNullable($nullable)
    {
        $this->_nullable = $nullable;
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
        $this->_created = $created;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->_updated;
    }

    /**
     * @param string $updated
     */
    public function setUpdated($updated)
    {
        $this->_updated = $updated;
    }

}
