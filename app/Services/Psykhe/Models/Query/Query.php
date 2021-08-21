<?php

namespace App\Services\Psykhe\Models\Query;

use App\Services\Psykhe\Models\Ocean;
use JsonSerializable;

class Query implements JsonSerializable
{
    /**
     * @var bool Indicator of whether or not additional debug information should be included in the Result
     *           Defaults to false
     */
    public bool $debug = false;

    /**
     * @var string|null Mood identifier (at time of this writing: baseline|adventurous|calm|confident|happy|romantic)
     *                  default behaviour is "baseline"
     */
    public ?string $mood = null;

    /**
     * @var string|null Recommendation Identifier, rather than basing a new recommendation off of a user+mood
     */
    public ?string $recommendation = null;

    /**
     * @var string|null Checkpoint passed from WEB-UI
     */
    public ?string $checkpoint = null;

    /**
     * @var string|null User Identifier for Recommendation generation and Savelist lookups.
     *                  May be omitted if user is not logged in
     */
    public ?string $user = null;

    /**
     * @var Ocean|null OCEAN scores to use as a basis for generating a new recommendation.
     *                 Should only be filled when user is not specified
     */
    public ?Ocean $ocean = null;

    /**
     * @var string[] Array of brand identifiers. If empty, any brand is permitted.
     */
    public array $brands = [];

    /**
     * @var string[] Array of category identifiers. If empty, any category is permitted.
     */
    public array $categories = [];

    /**
     * @var string[] Array of color identifiers. If empty, any color is permitted.
     */
    public array $colors = [];

    /**
     * @var string[] Array of occasion identifiers. If empty, any occasion is permitted.
     */
    public array $occasions = [];

    /**
     * @var string[] Array of option identifiers. If empty, defaults to ["!size-inclusive"].
     */
    public array $options = [];

    /**
     * @var string[] Array of partner identifiers. If empty, any partner is permitted.
     */
    public array $partners = [];

    /**
     * @var int[] Array of [min, max]. If empty, prices are unconstrained. A max of 0 is treated as "no maximum"
     */
    public object $price;

    /**
     * @var int[] Array of PSYKHE product identifiers. If empty, any product is permitted.
     */
    public array $products = [];

    /**
     * @var string[] Array of savelists slugs for the current user. If empty, defaults to ["!disliked"]
     */
    public array $savelists = [];

    /**
     * @var int[] Array of [offset, count], determining which "page" of each result pool to return.
     *            If empty, defaults to an offset of 0. The default count is unspecified.
     */
    public array $limit = [];

    /**
     * @var null
     */
    public ?string $search = null;

    public function jsonSerialize()
    {
        $data = [];

        if ($this->debug) {
            $data['debug'] = true;
        }

        if ($this->mood && $this->mood != 'baseline') {
            $data['mood'] = $this->mood;
        }
        foreach (
            [
                'recommendation',
                'checkpoint',
                'user',
                'ocean',
                'categories',
                'colors',
                'occasions',
                'options',
                'partners',
                'price',
                'savelists',
                'limit',
                'brands',
                'products',
                'search',
            ] as $property
        ) {
            if (isset($this->{$property}) && $this->{$property}) {
                $data[$property] = $this->{$property};
            }
        }

        return (object) $data;
    }
}
