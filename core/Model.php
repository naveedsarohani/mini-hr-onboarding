<?php

namespace Core;

use Closure;
use Core\Database;
use ReflectionClass;

abstract class Model
{
    protected array $attributes = [];
    protected array $wheres = [];
    protected array $with = [];
    protected array $orderBys = [];
    protected int $limit = 0;
    protected int $offset = 0;

    protected static array $queryInstances = [];

    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    public function fill(array $data): static
    {
        $this->attributes = array_merge($this->attributes, $data);
        return $this;
    }

    public function getTableName(): string
    {
        $class = explode('\\', static::class);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', end($class))) . 's';
    }

    public static function getQueryInstance(): static
    {
        $calledClass = static::class;

        if (!isset(static::$queryInstances[$calledClass])) {
            static::$queryInstances[$calledClass] = new static;
        }

        return static::$queryInstances[$calledClass];
    }

    protected static function resetQuery(): void
    {
        static::$queryInstances = [];
    }

    public static function where($column, $operator = null, $value = null): static
    {
        return static::getQueryInstance()->whereInstance($column, $operator, $value);
    }

    protected function whereInstance(string $column, mixed $operatorOrValue, mixed $value = null): static
    {
        if ($value === null) {
            $operator = '=';
            $value = $operatorOrValue;
        } else {
            $operator = $operatorOrValue;
        }

        $this->wheres[] = [$column, $operator, $value];
        return $this;
    }

    public static function when($condition, Closure $builder, ?Closure $default = null): static
    {
        return static::getQueryInstance()->whenInstance($condition, $builder, $default);
    }

    protected function whenInstance($condition, Closure $builder, ?Closure $default = null): static
    {
        if ($condition) {
            return $builder($this, $condition) ?? $this;
        }

        if ($default) {
            return $default($this, $condition) ?? $this;
        }

        return $this;
    }

    public static function with(...$relations): static
    {
        $instance = static::getQueryInstance();
        return $instance->withInstance(...$relations);
    }

    protected function withInstance(...$relations): static
    {
        $this->with = array_merge($this->with, $relations);
        return $this;
    }

    public static function orderBy(string $column, string $direction = 'asc'): static
    {
        $instance = static::getQueryInstance();
        return $instance->orderByInstance($column, $direction);
    }

    protected function orderByInstance(string $column, string $direction = 'asc'): static
    {
        $this->orderBys[] = [
            'column' => $column,
            'direction' => strtolower($direction) == 'desc' ? 'desc' : 'asc'
        ];
        return $this;
    }

    public static function limit(int $limit): static
    {
        $instance = static::getQueryInstance();
        return $instance->limitInstance($limit);
    }

    protected function limitInstance(int $limit): static
    {
        $this->limit = $limit;
        return $this;
    }

    public static function offset(int $offset): static
    {
        $instance = static::getQueryInstance();
        return $instance->offsetInstance($offset);
    }

    protected function offsetInstance(int $offset): static
    {
        $this->offset = $offset;
        return $this;
    }

    public function get(): array
    {
        $sql = "select * from {$this->getTableName()}";
        $params = [];

        if (!empty($this->wheres)) {
            $conditions = [];
            foreach ($this->wheres as $index => [$col, $op, $val]) {
                $paramKey = "param$index";

                $conditions[] = "$col $op :$paramKey";
                $params[$paramKey] = $val;
            }
            $sql .= " where " . implode(" and ", $conditions);
        }

        if (!empty($this->orderBys)) {
            $orderParts = array_map(fn($o) => "`{$o['column']}` {$o['direction']}", $this->orderBys);
            $sql .= ' order by ' . implode(', ', $orderParts);
        }

        if ($this->limit > 0) {
            $sql .= " limit {$this->limit}";
        }

        if ($this->offset > 0) {
            $sql .= " offset {$this->offset}";
        }

        // die($sql);
        $stmt = Database::instance()->query($sql, $params);
        $rows = $stmt->fetchAll();

        $models = array_map(fn($row) => new static($row), $rows);

        if (!empty($this->with)) {
            foreach ($models as $model) {
                foreach ($this->with as $relation) {
                    if (method_exists($model, $relation)) {
                        $model->{$relation} = $model->$relation();
                    }
                }
            }
        }

        static::resetQuery();
        return $models;
    }

    public function first(): ?static
    {
        $this->limitInstance(1);
        $results = $this->get();
        return $results[0] ?? null;
    }

    public static function all(): ?array
    {
        $instance = static::getQueryInstance();
        return $instance->allInstance();
    }

    protected function allInstance(): ?array
    {
        $results = $this->get();
        return $results ?? [];
    }

    public static function find($id): ?static
    {
        return static::where($id ? 'id' : null, $id)->first();
    }

    public static function create(array $data): static
    {
        $model = new static();
        $model->fill($data);

        $table = $model->getTableName();
        $columns = array_keys($data);
        $placeholders = array_map(fn($c) => ':' . $c, $columns);

        $sql = "insert into {$table} (" . implode(',', $columns) . ") values (" . implode(',', $placeholders) . ")";
        Database::instance()->query($sql, $data);

        $id = Database::instance()->lastInsertId();
        return static::find($id);
    }

    public function update(array $data): bool
    {
        $table = $this->getTableName();
        $updates = [];

        foreach ($data as $column => $value) {
            $updates[] = "$column = :$column";
        }

        $sql = "update {$table} set " . implode(', ', $updates) . " where id = :id";
        $data['id'] = $this->id;

        return Database::instance()->query($sql, $data)->rowCount() > 0;
    }

    public function delete(): bool
    {
        $sql = "delete from {$this->getTableName()} where id = :id";
        return Database::instance()->query($sql, ['id' => $this->id])->rowCount() > 0;
    }

    public function paginate(int $perPage = 10, int $page = 1): array
    {
        $this->limitInstance($perPage)->offsetInstance(($page - 1) * $perPage);
        $data = $this->get();

        $countSql = "select count(*) from {$this->getTableName()}";
        $params = [];

        if (!empty($this->wheres)) {
            $conditions = [];
            foreach ($this->wheres as $index => [$col, $op, $val]) {
                $paramKey = "param$index";
                $conditions[] = "$col $op :$paramKey";
                $params[$paramKey] = $val;
            }
            $countSql .= " where " . implode(" and ", $conditions);
        }

        $total = Database::instance()->query($countSql, $params)->fetchColumn();

        return [
            'data' => array_map(fn($r) => $r?->toArray(), $data),
            'total' => (int) $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    protected function toArray()
    {
        $fields = $this->attributes;

        foreach ($fields as $key => $value) {
            if ($value instanceof Model) {
                $fields[$key] = $value->attributes;
            }
        }

        return $fields;
    }

    public function hasOne(string $relatedClass, ?string $foreignKey = null, string $localKey = 'id')
    {
        $foreignKey = $foreignKey ?: strtolower((new ReflectionClass($this))->getShortName()) . '_id';
        return $relatedClass::where($foreignKey, $this->$localKey)->first();
    }

    public function hasMany(string $relatedClass, ?string $foreignKey = null, string $localKey = 'id')
    {
        $foreignKey = $foreignKey ?: strtolower((new ReflectionClass($this))->getShortName()) . '_id';
        return $relatedClass::where($foreignKey, $this->$localKey)->get();
    }

    public function belongsTo(string $relatedClass, ?string $foreignKey = null, string $ownerKey = 'id')
    {
        $foreignKey = $foreignKey ?: strtolower((new ReflectionClass($relatedClass))->getShortName()) . '_id';
        return $relatedClass::where($ownerKey, $this->$foreignKey)->first();
    }
}
