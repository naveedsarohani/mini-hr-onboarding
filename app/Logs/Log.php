<?php

namespace App\Logs;

use Core\Mongo;
use MongoDB\BSON\ObjectId;
use MongoDB\Collection;

class Log
{
    protected Collection $collection;

    public function __construct(?string $collection = null)
    {
        $database = new Mongo(collection: $collection);
        $this->collection = $database->collection;
    }

    public function get(array $filter = []): array
    {
        return $this->collection->find($filter, ['sort' => ['timestamp' => -1]])->toArray();
    }

    public function create(array $data): bool
    {
        $data = [...$data, 'timestamp' => new \MongoDB\BSON\UTCDateTime()];

        $created = $this->collection->insertOne($data);
        return $created->getInsertedCount() > 0;
    }

    public function find(string $id): ?array
    {
        $log = $this->collection->findOne(['_id' => new ObjectId($id)]);
        return $log ? (array) $log : null;
    }

    public function delete(string $id): bool
    {
        $result = $this->collection->deleteOne(['_id' => new ObjectId($id)]);
        return $result->getDeletedCount() > 0;
    }
}
