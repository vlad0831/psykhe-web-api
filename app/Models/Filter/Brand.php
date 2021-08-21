<?php

namespace App\Models\Filter;

use App\Components\FallbackCache\FallbackCache;
use Exception;
use Facades\App\Services\Psykhe\PsykheCatalogService;
use Illuminate\Support\Facades\Facade;
use function iterator_to_array;

class Brand extends Facade
{
    public static function List(): iterable
    {
        $brands = FallbackCache::remember(__METHOD__.'::brands', 60 * 60, function () {
            return iterator_to_array(PsykheCatalogService::Brands(), false);
        });

        if (! $brands) {
            throw new Exception('Failed to retrieve brands');
        }

        yield from $brands;
    }
}
