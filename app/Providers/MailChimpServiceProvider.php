<?php

namespace App\Providers;

use App\Services\MailChimp\MailChimp;
use DrewM\MailChimp\MailChimp as MailChimpApi;
use Illuminate\Support\ServiceProvider;

class MailChimpServiceProvider extends ServiceProvider
{
    public function register()
    {
        $config = config('mailchimp');

        $this->app->singleton(MailChimpApi::class, function ($app) use ($config) {
            if (! $config['enabled']) {
                return null;
            }

            return new MailChimpApi($config['api_key']);
        });

        $this->app->bind(MailChimp::class, function ($app) use ($config) {
            return new MailChimp(
                $app->make(MailChimpApi::class),
                $config['enabled'],
                $config['list_id']
            );
        });
    }
}
