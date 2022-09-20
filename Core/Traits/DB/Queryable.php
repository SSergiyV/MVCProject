<?php

namespace Core\Traits\DB;

use Core\Db;
use Exception;
use PDO;

trait Queryable
{
    static protected string|null $tableName = "";

    static protected string $query = "";

    protected array $commands = [];

    public static function create(array $fields): int
    {
        $vars = static::preparedQueryVars($fields);

        $query = "INSERT INTO " . static::$tableName . " ({$vars['keys']}) VALUES ({$vars['placeholders']})";
        $query = Db::connect()->prepare($query);

        $query->execute($fields);

        return (int)Db::connect()->lastInsertId();
    }

    public static function all(): static
    {
        static::$query = "SELECT * FROM " . static::$tableName;
        $obj = new static();
        $obj->commands[] = 'all';

        return $obj;
    }

    public static function find(int $id)
    {
        $query = "SELECT * FROM " . static::$tableName . " WHERE id=:id";

        $query = Db::connect()->prepare($query);
        $query->bindParam('id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    public static function findBy(string $column, $value)
    {
        $query = "SELECT * FROM " . static::$tableName . " WHERE {$column}=:{$column}";

        $query = Db::connect()->prepare($query);
        $query->bindParam($column, $value);
        $query->execute();

        return $query->fetchObject(static::class);
    }

    public function orderBy($column, $direction = 'ASC'): static
    {
        if ($this->allowMethod(['all', 'select'])) {
            $this->commands[] = 'order';
            static::$query .= " ORDER BY {$column} {$direction}";
        }
        return $this;
    }

    public function get()
    {
        return Db::connect()->query(static::$query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    protected function allowMethod(array $allowedMethods): bool
    {
        foreach ($allowedMethods as $method) {
            if (in_array($method, $this->commands)) {
                return true;
            }
        }
        return false;
    }

    protected static function preparedQueryVars(array $fields): array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders),
        ];
    }

    public static function delete(string $id) {

        $query = "DELETE FROM " . static::$tableName . " WHERE id=:id";
        $query = Db::connect() -> prepare($query);
        $query -> bindValue('id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    public static function select(array $columns = ['*']): static {

        static::$query = "";
        static::$query = "SELECT " . implode(',', $columns) . " FROM " . static::$tableName . " ";
        $obj = new static();
        $obj->commands[] = 'select';

        return $obj;
    }

    /**
     * @throws Exception
     */
    public function where(string $column, string $operator, $value): static {

        if (!$this->allowMethod(['select'])) {
            throw new Exception('Wrong request');
        }

        static::$query .= " WHERE {$column}{$operator}:{$column} ";
        $this->commands[] = 'where';

        return $this;
    }

    public function destroy() {

        if (!isset($this -> id)) {
            return $this;
        }
        return static::delete($this -> id);
    }

    public function update(array $data) {

        if (!$this -> allowMethod(['select', 'all', 'where'])) {
            throw new Exception('Wrong request');
        }
        if (isset($this -> id)) {
            return $this;
        }

        $query = "UPDATE " . static::$tableName . ' SET ' . static::buildPlaceholder($data) . " WHERE id=:id";
        $query = Db::connect() -> prepare($query);

        foreach ($data as $key => $value) {
            $query -> bindValue(":{$key}", $value);
        }

        $query -> bindValue('id', $this -> id, PDO::PARAM_INT);
        $query -> execute();

        return static::find($this -> id);
    }

    private static function buildPlaceholder(array $data) {

        $placeholder = [];

        foreach ($data as $key => $value) {
            $placeholder[] = " {$key}=:{$key}";
        }
        return implode(', ', $placeholder);
    }

}