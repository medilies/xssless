<?php

use Medilies\Xssless\Dompurify\DompurifyCli;
use Medilies\Xssless\Dompurify\DompurifyCliConfig;

it('cleans via exec', function () {
    $cleaner = (new DompurifyCli)->configure(new DompurifyCliConfig(
        'node',
        'npm',
    ));

    $clean = $cleaner->exec('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});
