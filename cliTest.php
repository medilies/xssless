<?php

require __DIR__.'/vendor/autoload.php';

use Medilies\Xssless\Dompurify\DompurifyService;
use Medilies\Xssless\Xssless;

$service = (new Xssless)->start([
    'host' => '127.0.0.1',
    'port' => 63000,
    'class' => DompurifyService::class,
]);

$terminate = function ($signal) use ($service) {
    echo "Terminating...\n";
    $service->stop();
    exit;
};

pcntl_signal(SIGTERM, $terminate);
pcntl_signal(SIGINT, $terminate);

while ($service->isRunning()) {
    $output = $service->getIncrementalOutput();
    $errorOutput = $service->getIncrementalErrorOutput();

    echo $output;
    if (! empty($errorOutput)) {
        echo 'Error: '.$errorOutput;
    }

    pcntl_signal_dispatch();

    // Sleep for a short period to avoid busy-waiting
    usleep(100000);
}

$service->throwIfFailedOnExit();
