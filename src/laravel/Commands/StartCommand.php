<?php

namespace Medilies\Xssless\Laravel\Commands;

use Illuminate\Console\Command;
use Medilies\Xssless\Interfaces\ServiceInterface;
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

        if (is_null($service)) {
            $this->info('The current driver is not a service to start.');

            return;
        }

        $this->onTermination($service);

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

    private function onTermination(ServiceInterface $service): void
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' || ! extension_loaded('pcntl')) {
            return;
        }

        $terminate = function ($signal) use ($service) {
            $this->warn("Terminating...\n");
            $service->stop();
            exit;
        };

        pcntl_signal(SIGTERM, $terminate);
        pcntl_signal(SIGINT, $terminate);
    }
}
