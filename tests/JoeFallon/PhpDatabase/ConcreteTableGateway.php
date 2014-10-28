<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\PhpDatabase\AbstractTableGateway;
use JoeFallon\PhpTime\Chronograph;
use PDO;
use Psr\Log\LoggerInterface;

/**
 * @author    Joseph Fallon <joseph.t.fallon@gmail.com>
 * @copyright Copyright 2014 Joseph Fallon (All rights reserved)
 * @license   MIT
 */
class ConcreteTableGateway extends AbstractTableGateway
{
    public function __construct(PDO $db,
                                $tableName,
                                Chronograph $timer,
                                LoggerInterface $logger)
    {
        parent::__construct($db, $tableName, $timer, $logger);
    }


    /**
     * create
     *
     * @param GtwyTestEntity $e
     *
     * @return integer
     */
    public function create(GtwyTestEntity $e)
    {
        return $this->baseCreate($e);
    }


    /**
     * baseDelete
     *
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->baseDelete($id);
    }


    /**
     * retrieve
     *
     * @param int $id
     *
     * @return GtwyTestEntity
     */
    public function retrieve($id)
    {
        return $this->baseRetrieve($id);
    }


    /**
     * deleteByNullableVal
     *
     * @param integer $val
     *
     * @return integer
     */
    public function deleteByNullableVal($val)
    {
        return $this->baseDeleteBy('nullable_val', $val);
    }


    /**
     * retrieveByNullableValue
     *
     * @param int $val
     *
     * @return array
     */
    public function retrieveByNullableValue($val)
    {
        return $this->baseRetrieveBy('nullable_val', $val);
    }


    /**
     * retrieveByIds
     *
     * @param array $ids
     *
     * @return array
     */
    public function retrieveByIds($ids)
    {
        return $this->baseRetrieveByIds($ids);
    }


    /**
     * retrieveByIsNull
     *
     * @param string $fieldName
     *
     * @return array
     */
    public function retrieveByIsNull($fieldName)
    {
        return $this->baseRetrieveByIsNull($fieldName);
    }


    /**
     * retrieveByNotEqual
     *
     * @param string $fieldName
     * @param string $fieldValue
     *
     * @return array
     */
    public function retrieveByNotEqual($fieldName, $fieldValue)
    {
        return $this->baseRetrieveByNotEqual($fieldName, $fieldValue);
    }


    /**
     * setNullableValNull
     *
     * @param int $val
     *
     * @return int
     */
    public function setNullableValNull($val)
    {
        return $this->baseSetFieldNull('nullable_val', $val);
    }


    /**
     * update
     *
     * @param GtwyTestEntity $e
     *
     * @return int
     */
    public function update(GtwyTestEntity $e)
    {
        return $this->baseUpdate($e);
    }


    public function countByNullableValue($val)
    {
        return $this->baseCountBy('nullable_val', $val);
    }


    /**
     * convertArrayToObject
     *
     * @param array $array
     *
     * @return GtwyTestEntity
     */
    protected function convertArrayToObject($array)
    {
        $entity                = new GtwyTestEntity();
        $entity->_name         = $array['name'];
        $entity->_nullable_val = $array['nullable_val'];
        $entity->setCreated($array['created']);
        $entity->setUpdated($array['updated']);
        $entity->setId($array['id']);

        return $entity;
    }


    /**
     * convertObjectToArray
     *
     * @param GtwyTestEntity $object
     *
     * @return array
     */
    protected function convertObjectToArray($object)
    {
        /* @var $object GtwyTestEntity */
        $array                 = array();
        $array['id']           = $object->getId();
        $array['name']         = $object->_name;
        $array['nullable_val'] = $object->_nullable_val;
        $array['created']      = $object->getCreated();
        $array['updated']      = $object->getCreated();

        return $array;
    }
}
