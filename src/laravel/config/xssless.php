<?php

use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Dompurify\DompurifyServiceConfig;

return [
    'default' => 'dompurify-cli',

    'drivers' => [
        'dompurify-cli' => new DompurifyCliConfig(
            node: env('NODE_PATH'), // @phpstan-ignore argument.type
            npm: env('NPM_PATH'), // @phpstan-ignore argument.type
            binary: null,
            tempFolder: null,
        ),
        'dompurify-service' => new DompurifyServiceConfig(
            node: env('NODE_PATH'), // @phpstan-ignore argument.type
            npm: env('NPM_PATH'), // @phpstan-ignore argument.type
            host: '127.0.0.1',
            port: 63000,
            binary: null,
        ),
    ],
];
