<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;

class CacheRepository
{
    const CACHE_EXPIRED_HOURS = 1;

    /**
     * Returns a service class instance.
     *
     * @return CacheRepository
     */
    public static function getInstance()
    {
        return resolve(self::class);
    }

    /**
     * Stores contents in Redis cache.
     *
     * @param $key
     * @param $params
     * @param bool $serialize
     */
    public function remember($key, $params, $serialize = true)
    {
        Cache::remember($key, self::CACHE_EXPIRED_HOURS * 60 * 60, function () use ($serialize, $params) {
            return $serialize ? serialize($params) : $params;
        });
    }

    /**
     * Stores contents in Redis cache.
     *
     * @param $key
     * @param $params
     * @param bool $serialize
     */
    public function store($key, $params, $serialize = true)
    {
        Cache::rememberForever($key, function () use ($serialize, $params) {
            return $serialize ? serialize($params) : $params;
        });
    }

    /**
     * Forgets a key value.
     *
     * @param $key
     *
     * @return bool
     */
    public function forget($key)
    {
        return Cache::forget($key);
    }

    /**
     * Retrieves content from Redis cache.
     *
     * @param $key
     * @param bool $unSerialize
     *
     * @return mixed
     */
    public function read($key, $unSerialize = true)
    {
        return $unSerialize ? unserialize(Cache::get($key)) : Cache::get($key);
    }
}
