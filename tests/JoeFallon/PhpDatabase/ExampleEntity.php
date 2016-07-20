<?php
namespace tests\JoeFallon\PhpDatabase;

class ExampleEntity
{
    /** @var int */
    private $_id;
    /** @var string */
    private $_name;
    /** @var string */
    private $_nullable;
    /** @var int */
    private $_numeral;
    /** @var string */
    private $_created;
    /** @var string */
    private $_updated;

    public function __construct()
    {
        $this->_id = 0;
        $this->_name = "";
        $this->_nullable = null;
        $this->_numeral = 0;
        $this->_created = "";
        $this->_updated = "";
    }

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
        $this->_id = (int)$id;
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
        $this->_name = (string)$name;
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
     * @return int
     */
    public function getNumeral()
    {
        return $this->_numeral;
    }

    /**
     * @param int $numeral
     */
    public function setNumeral($numeral)
    {
        $this->_numeral = (int)$numeral;
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
