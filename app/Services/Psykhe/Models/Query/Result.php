<?php

namespace App\Services\Psykhe\Models\Query;

use JsonSerializable;

class Result implements JsonSerializable
{
    /**
     * @var string Identifier of the relevant query. Suitable for use as a cache key.
     */
    public string $identifier;

    /**
     * @var string one of pending|processing|complete|error|expired
     *             "pending" indicates that the query has not yet started processing, and there may be a longer wait
     *             "processing" indicates that the result is still being calculated.
     *             "complete" indicates that all processing is done. pools and products will be filled.
     *             "error" indicates that there was a permanent error when processing the query. error will be filled.
     *             "expired" indicates that the query cannot be resolved due to a missing dependency.
     *                       if returned by a Create, this is due to a specified dependency being expired, and the
     *                       request cannot be completed as-given.
     *                       if returned by a Resolve, this is likely due to the specific result having expired, and the
     *                       query should be re-created.
     */
    public string $status;

    /**
     * @var string if status is error, error contains additional information about the specific error condition. This is
     *             not intended to be exposed to the end-user.
     */
    public ?string $error;

    /**
     * @var string|null Recommendation Identifier which can be used to re-request the specific result order again.
     *                  Only filled if status is "complete".
     */
    public ?string $recommendation;

    /**
     * @var string|null Checkpoint returned from QE
     */
    public ?string $checkpoint;

    /**
     * @var \App\Services\Psykhe\Models\Query\Pool[]|null Array of Query Result Pools, indicating recommended order.
     *                                                    Only filled if status is "complete".
     */
    public ?array $pools;

    /**
     * @var array<int,\App\Services\Psykhe\Models\Catalog\Product>|null array of product data, keyed by Identifier
     *                                                                  Only filled if status is "complete"
     *                                                                  A single product may be referenced by multiple
     *                                                                  Pools
     */
    public ?array $products;

    /**
     * @var \App\Services\Psykhe\Models\Query\Query|null Query which will retrieve the "next" results, for pagination
     *                                                   Only filled if status is "complete"
     *                                                   May be null if there is known to be no further data
     */
    public ?Query $next;

    /**
     * @var mixed (no stable API) debug information regarding the query
     */
    public $debug = null;

    public function jsonSerialize()
    {
        $data = [];
        foreach (
            [
                'identifier',
                'status',
                'error',
                'recommendation',
                'checkpoint',
                'pools',
                'products',
                'next',
                'debug',
                'hasMore',
            ] as $property
        ) {
            if (isset($this->{$property}) && $this->{$property}) {
                $data[$property] = $this->{$property};
            }
        }

        if (isset($data['status']) && $data['status'] === 'complete') {
            if (! isset($data['pools'])) {
                $data['pools'] = [];
            }

            if (! isset($data['products'])) {
                $data['products'] = (object) [];
            }
        }

        return (object) $data;
    }
}
