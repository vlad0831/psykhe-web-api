<?php

namespace App\Components\FallbackCache;

use Cache;
use Exception;

class FallbackCache
{
    public static function remember($key, $duration, $callback)
    {
        try {
            $result = Cache::remember($key, $duration, $callback);
            Cache::put($key.'::fallback', $result);
        } catch (Exception $e) {
            report($e);
            $result = Cache::get($key.'::fallback');

            if ($result !== null) {
                Cache::put($key, $result, $duration);
            }
        }

        return $result;
    }
}
