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
        return 0;
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function delete($id)
    {
        return 0;
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
     * @param array $array
     *
     * @return ExampleEntity
     */
    protected function mapArrayToObject($array)
    {
        $object = new ExampleEntity();

        $object->setId($array['id']);
        $object->setName($array['name']);
        $object->setNullable($array['nullable']);
        $object->setNumeral($array['numeral']);
        $object->setCreated($array['created']);
        $object->setUpdated($array['updated']);

        return $object;
    }
}
