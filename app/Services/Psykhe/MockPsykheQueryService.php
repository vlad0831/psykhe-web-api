<?php

namespace App\Services\Psykhe;

use App\Services\Psykhe\Models\Catalog\Brand;
use App\Services\Psykhe\Models\Catalog\Offer;
use App\Services\Psykhe\Models\Catalog\Partner;
use App\Services\Psykhe\Models\Catalog\Product;
use App\Services\Psykhe\Models\Query\Pool;
use App\Services\Psykhe\Models\Query\Query;
use App\Services\Psykhe\Models\Query\Result;
use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use stdClass;

class MockPsykheQueryService implements Contracts\PsykheQueryService
{
    protected static string $bucket = 'dev.psykhe';

    protected static string $prefix = 'mock/qe/';

    protected static string $key = 'not_pool_8.json';

    protected $s3;

    protected Contracts\PsykheService $psykhe;

    public function __construct(Contracts\PsykheService $psykhe)
    {
        $this->psykhe = $psykhe;
        $this->s3     = new S3Client([
            'version' => 'latest',
            'region'  => 'eu-west-1',
        ]);
    }

    protected function queryResult(stdClass $response): Result
    {
        $result = new Result();

        if (isset($response->debug)) {
            $result->debug = $response->debug;
        }
        $result->debug      = 'mock data';
        $result->identifier = $response->identifier;

        if (isset($response->recommendation)) {
            $result->recommendation = $response->recommendation;
        }
        $result->status = $response->status ?? 'complete';
        $result->error  = $response->error  ?? null;

        if (isset($response->pools)) {
            foreach ($response->pools as $response_pool) {
                $pool = new Pool();

                if (isset($pool->debug)) {
                    $pool->debug = $response_pool->debug;
                }
                $pool->identifier = $response_pool->identifier;
                $pool->weight     = $response_pool->weight;
                $pool->order      = $response_pool->order;
                $result->pools[]  = $pool;
            }
        }

        if (isset($response->products)) {
            foreach ($response->products as $response_product) {
                $product                    = new Product();
                $product->identifier        = $response_product->identifier;
                $product->name              = $response_product->name;
                $product->slug              = $response_product->slug;
                $product->category          = $response_product->categories[0];
                $product->description       = $response_product->description_short;
                $product->brand             = new Brand();
                $product->brand->identifier = $response_product->brand->identifier;
                $product->brand->name       = $response_product->brand->name;
                $product->images            = $response_product->images;

                foreach ($response_product->offers as $response_product_offer) {
                    $offer                      = new Offer();
                    $offer->currency            = $response_product_offer->currency;
                    $offer->price               = $response_product_offer->price;
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

    private function getData()
    {
        try {
            $result = $this->s3->getObject([
                'Bucket' => static::$bucket,
                'Key'    => static::$prefix.static::$key,
            ]);

            $content = (string) $result['Body'];

            return json_decode($content);
        } catch (AwsException $e) {
            Log::error($e->getMessage());
        }
    }

    public function CreateQuery(Query $query): Result
    {
        return $this->queryResult($this->getData());
    }

    public function ResolveQuery(string $identifier, string $checkpoint): Result
    {
        return $this->queryResult($this->getData());
    }
}
