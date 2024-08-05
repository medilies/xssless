<?php

namespace Medilies\Xssless\Commands;

use Illuminate\Console\Command;
use Medilies\Xssless\Facades\Xssless;

class StartCommand extends Command
{
    /** @var string */
    protected $signature = 'xssless:start';

    /** @var string|null */
    protected $description = 'Start the xssless service';

    public function handle(): void
    {
        // TODO: take driver from config + check interface
        $service = Xssless::start(config('xssless.dompurify-service'));

        $terminate = function ($signal) use ($service) {
            $this->alert("Terminating...\n");
            $service->stop();
            exit;
        };

        pcntl_signal(SIGTERM, $terminate);
        pcntl_signal(SIGINT, $terminate);

        while ($service->isRunning()) {
            $output = $service->getIncrementalOutput();
            $errorOutput = $service->getIncrementalErrorOutput();

            $this->comment($output);
            if (! empty($errorOutput)) {
                $this->error($errorOutput);
            }

            pcntl_signal_dispatch();

            // Sleep for a short period to avoid busy-waiting
            usleep(100000);
        }

        $service->throwIfFailedOnExit();
    }
}
