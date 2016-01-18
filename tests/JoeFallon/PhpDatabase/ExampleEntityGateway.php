<?php
namespace tests\JoeFallon\PhpDatabase;

use JoeFallon\PhpDatabase\AbstractTableGateway;
use PDO;

class ExampleEntityGateway extends AbstractTableGateway
{
    const TABLE_NAME      = 'example_entity_table';
    const PRIMARY_KEY_COL = 'id';
    const CREATED_AT_COL  = 'created';
    const UPDATED_AT_COL  = 'updated';

    /**
     * @param PDO $pdo
     */
    public function __construct($pdo)
    {
        parent::__construct($pdo, self::TABLE_NAME, self::PRIMARY_KEY_COL);
        $this->setCreatedAtName(self::CREATED_AT_COL);
        $this->setUpdatedAtName(self::UPDATED_AT_COL);
    }

    /**
     * @param ExampleEntity $entity
     *
     * @return int
     */
    public function create(ExampleEntity $entity)
    {
        return $this->baseCreate($entity);
    }

    /**
     * @param $id
     *
     * @return ExampleEntity
     */
    public function retrieve($id)
    {
        return $this->baseRetrieve($id);
    }

    /**
     * @param ExampleEntity $entity
     *
     * @return int
     */
    public function update(ExampleEntity $entity)
    {
        return $this->baseUpdate($entity);
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return $this->baseDelete($id);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function retrieveByName($name)
    {
        return $this->baseRetrieveBy('name', $name);
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function retrieveByIds(array $ids)
    {
        return $this->baseRetrieveByIds($ids);
    }

    /**
     * @return array
     */
    public function retrieveByIsNull()
    {
        return $this->baseRetrieveByIsNull('nullable');
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function retrieveByNameNotEqual($name)
    {
        return $this->baseRetrieveByNotEqual('name', $name);
    }

    /**
     * @param string $value
     *
     * @return int
     */
    public function setFieldNullableNull($value)
    {
        return $this->baseSetFieldNull('nullable', $value);
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function deleteNameBy($name)
    {
        return $this->baseDeleteBy('name', $name);
    }


    /**
     * @param string $name
     *
     * @return mixed
     */
    public function countByName($name)
    {
        return $this->baseCountBy('name', $name);
    }

    public function truncateTable()
    {
        $pdo = $this->_pdo;
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0;');
        $pdo->exec('TRUNCATE TABLE `example_entity_table`');
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * @param ExampleEntity $object
     *
     * @return array
     */
    protected function mapObjectToArray($object)
    {
        /** @var ExampleEntity $object */
        $result = [
            'id'       => $object->getId(),
            'name'     => $object->getName(),
            'nullable' => $object->getNullable(),
            'numeral'  => $object->getNumeral(),
            'created'  => $object->getCreated(),
            'updated'  => $object->getUpdated()
        ];

        return $result;
    }

    /**
     * @param array $arr
     *
     * @return ExampleEntity
     */
    protected function mapArrayToObject($arr)
    {
        $object = new ExampleEntity();

        $object->setId($arr['id']);
        $object->setName($arr['name']);
        $object->setNullable($arr['nullable']);
        $object->setNumeral($arr['numeral']);
        $object->setCreated($arr['created']);
        $object->setUpdated($arr['updated']);

        return $object;
    }

}
