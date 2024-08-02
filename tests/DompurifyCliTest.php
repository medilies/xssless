<?php

use Medilies\Xssless\Dompurify\DompurifyCli;

it('cleans via exec', function () {
    $cleaner = new DompurifyCli([
        'node_path' => '/home/medilies/.nvm/versions/node/v20.15.1/bin/node',
    ]);

    $clean = $cleaner->exec('<IMG """><SCRIPT>alert("XSS")</SCRIPT>">');

    expect($clean)->toBe('<img>"&gt;');
});
