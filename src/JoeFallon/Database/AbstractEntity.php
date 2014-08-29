<?php
namespace JoeFallon\Database;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
abstract class AbstractEntity
{
    /** @var integer */
    private $_id;
    /** @var string */
    private $_created;
    /** @var string */
    private $_updated;
    /** @var array */
    protected $_validationMessages;
    
    
    /**
     * __construct
     */
    public function __construct()
    {
        $this->_id      = 0;
        $this->_created = '';
        $this->_updated = '';
    }


    /**
     * setId
     *
     * @param $val integer
     */
    public function setId($val)
    {
        $val = intval($val);
        $this->_id = $val;
    }
    
    
    /**
     * getId
     * 
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    
    /**
     * setCreated
     * 
     * @param string $val
     */
    public function setCreated($val)
    {
        $val = strval($val);
        $this->_created = $val;
    }
    
    
    /**
     * getCreated
     * 
     * @return string
     */
    public function getCreated()
    {
        return $this->_created;
    }
    
    
    /**
     * setCreated
     * 
     * @param string $val
     */
    public function setUpdated($val)
    {
        $val = strval($val);
        $this->_updated = $val;
    }
    
    
    /**
     * getUpdated
     * 
     * @return string
     */
    public function getUpdated()
    {
        return $this->_updated;
    }
    
    
    /**
     * isValid
     * 
     * @return bool
     */
    public abstract function isValid();
    
    
    /**
     * getValidationMessages
     * 
     * @return array
     */
    public function getValidationMessages()
    {
        return $this->_validationMessages;
    }
    
    
    /**
     * addValidationMessage
     * 
     * @param string $msg
     */
    protected function addValidationMessage($msg)
    {
        $msg = strval($msg);
        
        if(strlen($msg) > 0)
        {
            $this->_validationMessages[] = $msg;
        }
    }
}
