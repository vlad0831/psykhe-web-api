<?php

//use App\Components\AnalyticsEngine\AnalyticsChannel;

return [
    'domain'      => env('SITE_HOSTNAME', 'localhost'),
    'auth' => [
      'email_verification_required' => !!env('PSYKHE_EMAIL_VERIFICATION_REQUIRED', '0')
    ],
    'spa' => [
        'url'                     => env('PSYKHE_CLIENT_URL', 'https://psykhefashion.com'),
        'email_verification_path' => env('PSYKHE_CLIENT_EMAIL_VERIFICATION_PATH', '/verify-email'),
        'email_password_reset'    => env('PSYKHE_CLIENT_PASSWORD_RESET_PATH', '/password-reset'),
    ],
    'api'         => [
        'endpoint' => env('PSYKHE_API_ENDPOINT', ''),
        'username' => env('PSYKHE_API_USERNAME', ''),
        'password' => env('PSYKHE_API_PASSWORD', ''),
        'timeout'  => env('PSYKHE_API_TIMEOUT', 5)
    ],
    'analytics'   => [
//        'listeners' => [
//            AnalyticsChannel::ERRORS => json_decode(
//                env('PSYKHE_ANALYTICS_ERROR_LISTENERS', '[]'), true
//            ),
//            AnalyticsChannel::INTERACTIONS => json_decode(
//                env('PSYKHE_ANALYTICS_INTERACTION_LISTENERS', '[]'), true
//            ),
//        ],
    ],
    'facebook' => [
        'enabled'  => (
            env('PSYKHE_ANALYTICS_FACEBOOK_PIXEL_ID', '') &&
            env('PSYKHE_ANALYTICS_FACEBOOK_ENABLED', env('PSYKHE_ANALYTICS_ENABLED', '1'))
        ),
        'pixel_id' => env('PSYKHE_ANALYTICS_FACEBOOK_PIXEL_ID', '')
    ],
    'ga' => [
        'enabled' => (
            env('PSYKHE_ANALYTICS_GA_UA', '') &&
            env('PSYKHE_ANALYTICS_GA_ENABLED', env('PSYKHE_ANALYTICS_ENABLED', '1'))
        ),
        'ua'      => env('PSYKHE_ANALYTICS_GA_UA', '')
    ],
    'avatar' => [
        // Avatar disk can be 'local' or 's3'
        'disk' => 'avatar_' . env('PSYKHE_AVATAR_DISK', 'local')
    ]
];
