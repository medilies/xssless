<?php

namespace Medilies\Xssless\Commands;

use Illuminate\Console\Command;
use Medilies\Xssless\Facades\Xssless;

class SetupCommand extends Command
{
    /** @var string */
    protected $signature = 'xssless:setup';

    /** @var string|null */
    protected $description = 'Setup the xssless service';

    public function handle(): void
    {
        // TODO: take driver from config
        Xssless::setup(config('xssless.dompurify-service'));
    }
}
