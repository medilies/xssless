<?php

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyCliConfig;
use Tests\TestCase;

uses(TestCase::class)
    ->beforeAll(function () {
        (new DompurifyCli)
            ->configure(new DompurifyCliConfig('node', 'npm'))
            ->setup();
    })
    ->in(__DIR__.'/Dompurify');

