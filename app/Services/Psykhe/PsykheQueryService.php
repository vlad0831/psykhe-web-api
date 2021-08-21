<?php

namespace App\Services\Psykhe;

use App\Services\Psykhe\Models\Catalog\Brand;
use App\Services\Psykhe\Models\Catalog\Offer;
use App\Services\Psykhe\Models\Catalog\Partner;
use App\Services\Psykhe\Models\Catalog\Product;
use App\Services\Psykhe\Models\Query\Pool;
use App\Services\Psykhe\Models\Query\Query;
use App\Services\Psykhe\Models\Query\Result;
use GuzzleHttp\RequestOptions;
use stdClass;

class PsykheQueryService implements Contracts\PsykheQueryService
{
    protected static string $prefix = '/api/v3/queryengine/';

    protected Contracts\PsykheService $psykhe;

    public function __construct(Contracts\PsykheService $psykhe)
    {
        $this->psykhe = $psykhe;
    }

    protected function queryResult(stdClass $response): Result
    {
        $result = new Result();

        if (isset($response->debug)) {
            $result->debug = $response->debug;
        }

        if (isset($response->identifier)) {
            $result->identifier = $response->identifier;
        }

        if (isset($response->recommendation)) {
            $result->recommendation = $response->recommendation;
        }

        if (isset($response->checkpoint)) {
            $result->checkpoint = $response->checkpoint;
        }

        $result->status = $response->status;
        $result->error  = $response->error ?? $response->message ?? null;

        if (isset($response->pools)) {
            foreach ($response->pools as $response_pool) {
                $pool = new Pool();

                if (isset($response_pool->debug)) {
                    $pool->debug = $response_pool->debug;
                }
                $pool->identifier = $response_pool->identifier;
                $pool->weight     = $response_pool->weight;
                $pool->order      = $response_pool->order;
                $pool->has_more   = $response_pool->has_more;
                $result->pools[]  = $pool;
            }
        }

        if (isset($response->products)) {
            foreach ($response->products as $response_product) {
                $product                    = new Product();
                $product->identifier        = $response_product->identifier;
                $product->name              = $response_product->name;
                $product->slug              = $response_product->slug;
                $product->category          = $response_product->category;
                $product->description       = $response_product->description;
                $product->brand             = new Brand();
                $product->brand->identifier = $response_product->brand->identifier;
                $product->brand->name       = $response_product->brand->name;
                $product->images            = $response_product->images;

                foreach ($response_product->offers as $response_product_offer) {
                    $offer           = new Offer();
                    $offer->currency = $response_product_offer->currency;
                    $offer->price    = $response_product_offer->price;

                    if (
                        isset($response_product_offer->sale_price) && $response_product_offer->sale_price && $response_product_offer->sale_price != $response_product_offer->price
                    ) {
                        $offer->sale_price = $response_product_offer->sale_price;
                    }
                    $offer->url                 = $response_product_offer->url;
                    $offer->partner             = new Partner();
                    $offer->partner->identifier = $response_product_offer->partner->identifier;
                    $offer->partner->name       = $response_product_offer->partner->name;
                    $product->offers[]          = $offer;
                }

                $product->savelists = $response_product->savelists;
                $result->products[] = $product;
            }
        }

        return $result;
    }

    public function CreateQuery(Query $query): Result
    {
        if (config('app.debug')) {
            $query->debug = true;
        }
        $options = [
            RequestOptions::JSON => $query,
        ];

        $response = $this->psykhe->Request('POST', static::$prefix.'query', $options);

        return $this->queryResult($response);
    }

    public function ResolveQuery(string $identifier, string $checkpoint): Result
    {
        $suffix = ($checkpoint) ? '/'.$checkpoint : '';

        return $this->queryResult($this->psykhe->Request('GET', static::$prefix."query/{$identifier}{$suffix}"));
    }
}
