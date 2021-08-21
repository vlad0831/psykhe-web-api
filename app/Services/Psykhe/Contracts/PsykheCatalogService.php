<?php

namespace App\Services\Psykhe\Contracts;

interface PsykheCatalogService
{
    public function Brands($brand_identifier = null): iterable;

    public function Categories(): iterable;

    public function Menu(): iterable;
}
