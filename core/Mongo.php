<?php

namespace Core;

use MongoDB\Client;
use MongoDB\Collection;

class Mongo
{
    public Collection $collection;

    public function __construct(?string $database = null, ?string $collection = null)
    {
        $client = new Client(config('services.mongodb.uri'));

        $db = $client->selectDatabase(
            $database ?? config('services.mongodb.database')
        );

        $this->collection = $db->selectCollection(
            $collection ?? config('services.mongodb.collection')
        );
    }
}
