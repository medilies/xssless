<?php

namespace Medilies\Xssless\Laravel\Commands;

use Illuminate\Console\Command;
use Medilies\Xssless\Laravel\Facades\Xssless;

class StartCommand extends Command
{
    /** @var string */
    protected $signature = 'xssless:start';

    /** @var string|null */
    protected $description = 'Start the xssless service';

    public function handle(): void
    {
        // TODO: non Laravel command
        $service = Xssless::usingLaravelConfig()->start();

        $terminate = function ($signal) use ($service) {
            $this->warn("Terminating...\n");
            $service->stop();
            exit;
        };

        // ? Is this necessary
        pcntl_signal(SIGTERM, $terminate);
        pcntl_signal(SIGINT, $terminate);

        while ($service->isRunning()) {
            $output = $service->getIncrementalOutput();
            $errorOutput = $service->getIncrementalErrorOutput();

            if ($output !== '') {
                $this->line($output);
            }
            if ($errorOutput !== '') {
                $this->error($errorOutput);
            }

            pcntl_signal_dispatch();

            usleep(100_000);
        }
    }
}
