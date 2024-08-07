<?php

namespace Medilies\Xssless\Laravel\Commands;

use Illuminate\Console\Command;
use Medilies\Xssless\Laravel\Facades\Xssless;

class SetupCommand extends Command
{
    /** @var string */
    protected $signature = 'xssless:setup';

    /** @var string|null */
    protected $description = 'Setup the xssless service';

    public function handle(): void
    {
        Xssless::usingLaravelConfig()->setup();
    }
}
