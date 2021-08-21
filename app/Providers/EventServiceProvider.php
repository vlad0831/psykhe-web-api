<?php

namespace App\Providers;

use App\Events\PasswordRecoveryRequested;
use App\Events\ReferralWasAdded;
use App\Events\UserRegistered;
use App\Events\UserRegisteredVerificationRequested;
use App\Listeners\EnsureMarketingReferralRegistered;
use App\Listeners\EnsureMarketingRegistered;
use App\Listeners\SendPasswordRecoveryLink;
use App\Listeners\SendUserEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserRegistered::class => [
            SendUserEmailVerificationNotification::class,
            EnsureMarketingRegistered::class,
        ],
        UserRegisteredVerificationRequested::class => [
            SendUserEmailVerificationNotification::class,
        ],
        PasswordRecoveryRequested::class => [
            SendPasswordRecoveryLink::class,
        ],
        ReferralWasAdded::class => [
            EnsureMarketingReferralRegistered::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
