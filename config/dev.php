<?php

return [
    'debug' => [
        'sql' => !!env('DEV_DEBUG_SQL', env('DEV_DEBUG', FALSE))
    ]
];
