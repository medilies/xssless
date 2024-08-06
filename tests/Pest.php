<?php

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Medilies\Xssless\Tests\TestCase;

uses(TestCase::class)
    ->beforeAll(function () {
        (new DompurifyCli)
            ->configure(new DompurifyCliConfig('node', 'npm'))
            ->setup();
    })
    ->in(__DIR__.'/Dompurify');

// uses(TestCase::class)
//     ->beforeAll(function () {
//         (new DompurifyCli)
//             ->configure(new DompurifyCliConfig('node', 'npm'))
//             ->setup();
//     })
//     ->in(__DIR__);
