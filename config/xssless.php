<?php

// config for Medilies/Xssless

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyService;

return [
    'default' => 'dompurify-command',

    // TODO: config object
    'cleaners' => [
        'dompurify-command' => [
            'node_path' => env('NODE_PATH', 'node'),
            'class' => DompurifyCli::class,
        ],
        'dompurify-service' => [
            'node_path' => env('NODE_PATH', 'node'),
            'host' => '127.0.0.1',
            'port' => 63000,
            'class' => DompurifyService::class,
        ],
    ],
];
