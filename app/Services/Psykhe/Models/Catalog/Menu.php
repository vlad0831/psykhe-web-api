<?php

namespace App\Services\Psykhe\Models\Catalog;

class Menu
{
    public string $name;

    public array $path;

    public int $level;

    public int $sort_order;

    public ?string $category;
}
