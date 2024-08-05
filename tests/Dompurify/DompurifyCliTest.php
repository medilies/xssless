<?php

use Medilies\Xssless\Dompurify\DompurifyCli;

it('cleans via exec', function () {
    $cleaner = new DompurifyCli([
        'node_path' => 'node',
        'npm_path' => 'npm',
    ]);

    $clean = $cleaner->exec('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});
