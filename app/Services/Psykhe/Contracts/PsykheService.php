<?php

namespace App\Services\Psykhe\Contracts;

interface PsykheService
{
    public function Request($method, $url, $options = []);
}
