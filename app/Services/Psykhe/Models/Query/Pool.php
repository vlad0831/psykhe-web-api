<?php

namespace App\Services\Psykhe\Models\Query;

use JsonSerializable;

class Pool implements JsonSerializable
{
    /**
     * @var string Identifier of the source of the ordered PSYKHE Product Identifiers
     */
    public string $identifier;

    /**
     * @var float (0.0-1.0) the probability that a pool should be selected
     */
    public float $weight;

    /**
     * @var int[] PSYKHE Product Identifiers
     */
    public array $order;

    /**
     * @var mixed (no stable API) debug information regarding the source of the pool
     */
    public $debug = null;

    public bool $has_more;

    public function jsonSerialize()
    {
        $data               = [];
        $data['identifier'] = $this->identifier;
        $data['weight']     = $this->weight;
        $data['order']      = $this->order;
        $data['has_more']   = $this->has_more;

        if ($this->debug) {
            $data['debug'] = $this->debug;
        }

        return (object) $data;
    }
}
