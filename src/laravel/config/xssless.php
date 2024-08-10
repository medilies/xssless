<?php

use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;

return [
    'default' => 'dompurify-cli',

    'drivers' => [
        'dompurify-cli' => new DompurifyCliConfig(
            env('NODE_PATH', 'node'), // @phpstan-ignore argument.type
            env('NPM_PATH', 'npm'), // @phpstan-ignore argument.type
        ),
        'dompurify-service' => new DompurifyServiceConfig(
            env('NODE_PATH', 'node'), // @phpstan-ignore argument.type
            env('NPM_PATH', 'npm'), // @phpstan-ignore argument.type
            '127.0.0.1',
            63000,
        ),
    ],
];
