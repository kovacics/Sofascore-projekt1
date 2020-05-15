<?php

namespace src\model\abstractModel;


use src\db\DBPool;

abstract class AbstractDBModel implements DBModel
{

    /**
     * @var mixed
     */
    private $pk;

    /**
     * @var mixed
     */
    private $data;

    public const FETCH_ONE = 1;
    public const FETCH_ARRAY = 2;

    public function __construct($data = null)
    {
        $this->data = $data;
        if ($data !== null) {
            $this->pk = $data->{static::getPrimaryKeyColumn()};
        }
        if ($data === null) {
            foreach (static::getColumns() as $column) {
                $this->__set($column, null);
            }
        }
    }


    public function save(): void
    {
        $columns = static::getColumns();
        $values = [];
        $placeHolders = [];

        if ($this->pk === null) {

            foreach ($columns as $column) {
                $values[] = $this->data->$column;
                $placeHolders[] = "?";
            }

            $sql = "INSERT INTO " . $this->getTable() . " (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $placeHolders) . ")";

            DBPool::getPDO()->prepare($sql)->execute($values);
            $this->pk = DBPool::getPDO()->lastInsertId();
//            $this->data->{static::getPrimaryKey()} = $this->pk;

        } else {
            foreach ($columns as $column) {
                $values[] = $this->data->$column;
                $placeHolders[] = $column . " = ?";
            }

            $values[] = $this->pk;

            $sql = "UPDATE " . $this->getTable() . " SET " . implode(", ", $placeHolders)
                . " WHERE " . $this->getPrimaryKeyColumn() . " = ?";

            DBPool::getPDO()->prepare($sql)->execute($values);
        }
    }


    public static function get($pk)
    {

        $sql = "SELECT * FROM " . static::getTable() . " WHERE " . static::getPrimaryKeyColumn() . " = ?";

        $statement = DBPool::getPDO()->prepare($sql);
        $statement->execute([$pk]);

        if ($statement->rowCount() !== 1) {
            return null;
        }

        $fetchData = $statement->fetch();
        return new static($fetchData);
    }

    public static function getBy(string $field, $value, int $fetchType = self::FETCH_ARRAY)
    {

        $sql = "SELECT * FROM " . static::getTable() . " WHERE " . $field . " = ?";

        $statement = DBPool::getPDO()->prepare($sql);
        $statement->execute([$value]);

        if ($statement->rowCount() < 1) {
            if ($fetchType === self::FETCH_ONE) {
                return null;
            }
            return [];
        }

        $fetched = $statement->fetchAll();

        if ($fetchType === self::FETCH_ONE) {
            return new static($fetched[0]);
        }

        $all = [];
        foreach ($fetched as $data) {
            $all[] = new static($data);
        }
        return $all;
    }

    public static function getByFields(array $fields, int $fetchType = self::FETCH_ARRAY)
    {
        $whereCondition = " WHERE ";

        foreach ($fields as $key => $value) {
            $whereCondition .= "$key = $value and ";
        }

        $whereCondition = substr($whereCondition, 0, strlen($whereCondition) - 5);

        $sql = "SELECT * FROM " . static::getTable() . $whereCondition;

        $statement = DBPool::getPDO()->prepare($sql);
        $statement->execute();

        if ($statement->rowCount() < 1) {
            return null;
        }

        $fetched = $statement->fetchAll();

        if ($fetchType === self::FETCH_ONE) {
            return new static($fetched[0]);
        }

        $all = [];
        foreach ($fetched as $data) {
            $all[] = new static($data);
        }
        return $all;
    }

    public function delete()
    {

        if ($this->pk === null) {
            return;
        }
        DBPool::getPDO()->prepare(
            "DELETE FROM " . $this->getTable() . " WHERE " . $this->getPrimaryKeyColumn() . " = ?")
            ->execute([$this->pk]);

        $this->pk = null;
    }

    public static function deleteWithPK($pk)
    {

        DBPool::getPDO()->prepare(
            "DELETE FROM " . static::getTable() . " WHERE " . static::getPrimaryKeyColumn() . " = ?")
            ->execute([$pk]);

    }


    public static function getAll(): array
    {

        $sql = "SELECT * FROM " . static::getTable();

        $statement = DBPool::getPDO()->prepare($sql);
        $statement->execute();

        $fetched = $statement->fetchAll();

        $all = [];
        foreach ($fetched as $data) {
            $all[] = new static($data);
        }

        return $all;
    }

    public static abstract function getPrimaryKeyColumn();

    public static abstract function getTable();

    public static abstract function getColumns();

    public function serialize()
    {
        return serialize($this->data);
    }

    public function unserialize($string)
    {
        $this->data = unserialize($string);
    }

    public function getPrimaryKey()
    {
        return $this->pk;
    }

    public function __get($name)
    {
        return $this->data->$name;
    }


    public function __set(string $name, $value)
    {
        if (!isset($this->data)) {
            $this->data = new \stdClass();
        }
        return $this->data->$name = $value;
    }

    public function equals(Model $model)
    {

        if (get_class($this) !== get_class($model)) {
            return false;
        }

        return $this->pk === $model->getPrimaryKey();
    }

}