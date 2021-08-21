<?php

namespace App\Services\Psykhe\Models\Catalog;

class Category
{
    public array $path;

    public bool $in_parent;

    public ?string $description_with_login = null;

    public ?string $description_no_login = null;

    public ?string $heading_with_login = null;

    public ?string $heading_no_login = null;
}
