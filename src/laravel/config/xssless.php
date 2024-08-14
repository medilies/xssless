<?php

return [
    'default' => 'dompurify-cli',

    'drivers' => [
        'dompurify-cli' => new \Medilies\Xssless\Dompurify\DompurifyCliConfig(
            node: env('NODE_PATH', 'node'), // @phpstan-ignore argument.type
            npm: env('NPM_PATH', 'npm'), // @phpstan-ignore argument.type
            binary: null,
            tempFolder: null,
        ),

        'dompurify-service' => new \Medilies\Xssless\Dompurify\DompurifyServiceConfig(
            node: env('NODE_PATH', 'node'), // @phpstan-ignore argument.type
            npm: env('NPM_PATH', 'npm'), // @phpstan-ignore argument.type
            host: '127.0.0.1',
            port: 63000,
            binary: null,
        ),
    ],
];

// TODO: check classes exist
