<?php

namespace Core;

use Predis\Client;

class Redis
{
    protected $client, $key;

    public function __construct(string $key)
    {
        $this->client = new Client([
            'host' => config('services.redis.host'),
            'port' => config('services.redis.port'),
            'database' => config('services.redis.database'),
            'username' => config('services.redis.username'),
            'password' => config('services.redis.password'),
        ]);

        $this->key = $key;
    }

    public function get()
    {
        $val = $this->client->get($this->key);
        return $val ? json_decode($val, true) : null;
    }

    public function set($value, $ttl = 3600)
    {
        return $this->client->setex($this->key, $ttl, json_encode($value));
    }

    public function delete()
    {
        return $this->client->del([$this->key]);
    }
}
