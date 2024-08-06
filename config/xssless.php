<?php

use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;

return [
    'default' => 'dompurify-cli',

    'cleaners' => [
        'dompurify-cli' => new DompurifyCliConfig(
            env('NODE_PATH', 'node'),
            env('NPM_PATH', 'npm'),
        ),
        'dompurify-service' => new DompurifyServiceConfig(
            env('NODE_PATH', 'node'),
            env('NPM_PATH', 'npm'),
            '127.0.0.1',
            63000,
        ),
    ],
];
