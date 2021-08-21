<?php

return [
    'enabled' => env('MAILCHIMP_ENABLED') && env('MAILCHIMP_API_KEY'),
    'api_key' => env('MAILCHIMP_API_KEY'),
    'list_id' => env('MAILCHIMP_LIST_ID')
];
