<?php

namespace App\Services\Psykhe\Contracts;

use App\Services\Psykhe\Models\Query\Query;
use App\Services\Psykhe\Models\Query\Result;

interface PsykheQueryService
{
    public function CreateQuery(Query $query): Result;

    public function ResolveQuery(string $identifier, string $checkpoint): Result;
}
