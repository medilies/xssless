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
        $service = Xssless::start();

        if (is_null($service)) {
            $this->info('The current driver is not a service to start.');

            return;
        }

        while ($service->isRunning()) {
            $output = $service->getIncrementalOutput();
            $errorOutput = $service->getIncrementalErrorOutput();

            if ($output !== '') {
                $this->line($output);
            }
            if ($errorOutput !== '') {
                $this->error($errorOutput);
            }

            usleep(100_000);
        }
    }
}
