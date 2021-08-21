<?php

namespace App\Services\Psykhe\Models\Catalog;

class Product
{
    public string $identifier;

    public string $name;

    public string $slug;

    public string $category;

    public string $description;

    public Brand $brand;

    /**
     * @var string[] Product Image's "dynamic" URL, containing :width: and :height: placeholders
     */
    public array $images;

    /**
     * @var App\Services\Psykhe\Models\Catalog\Offer[]
     */
    public array $offers;

    /**
     * @var string[] Array of savelist slugs
     */
    public array $savelists;
}
