<?php

namespace App\Providers;

use App\Services\Psykhe\Contracts\PsykheCatalogService as PsykheCatalogServiceContract;
use App\Services\Psykhe\Contracts\PsykheDirectoryService as PsykheDirectoryServiceContract;
use App\Services\Psykhe\Contracts\PsykheQueryService as PsykheQueryServiceContract;
use App\Services\Psykhe\Contracts\PsykheService as PsykheServiceContract;
use App\Services\Psykhe\MockPsykheDirectoryService;
use App\Services\Psykhe\MockPsykheQueryService;
use App\Services\Psykhe\PsykheCatalogService;
use App\Services\Psykhe\PsykheDirectoryService;
use App\Services\Psykhe\PsykheQueryService;
use App\Services\Psykhe\PsykheService;
use GuzzleHttp\Client as Guzzle;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class PsykheServiceProvider extends ServiceProvider
{
    public function register()
    {
        $config = config('psykhe.api');

        $this->app->singleton(PsykheServiceContract::class, function ($app) use ($config) {
            $guzzle = $this->app->make(Guzzle::class, [
                'defaults' => array_merge(
                    [],
                    isset($config['timeout']) ? ['timeout' => $config['timeout']] : []
                ),
            ]);

            return new PsykheService(
                $guzzle,
                $config['endpoint'],
                $config['username'],
                $config['password']
            );
        });

        $this->app->singleton(PsykheCatalogServiceContract::class, function ($app) {
            $psykhe = $this->app->make(PsykheServiceContract::class);

            return new PsykheCatalogService($psykhe);
        });

        $this->app->singleton(PsykheDirectoryServiceContract::class, function ($app) {
            $psykhe = $this->app->make(PsykheServiceContract::class);

            if (config('app.env') == 'test') {
                Log::debug('MockPsykheDirectoryService');

                return new MockPsykheDirectoryService($psykhe);
            }

            return new PsykheDirectoryService($psykhe);
        });

        $this->app->singleton(PsykheQueryServiceContract::class, function ($app) {
            $psykhe = $this->app->make(PsykheServiceContract::class);

            if (config('app.env') == 'test') {
                Log::debug('MockPsykheQueryService');

                return new MockPsykheQueryService($psykhe);
            }

            return new PsykheQueryService($psykhe);
        });
    }
}
