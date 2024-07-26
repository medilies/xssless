<?php

namespace Medilies\Xssless\Commands;

use Illuminate\Console\Command;

class XsslessCommand extends Command
{
    public $signature = 'xssless';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
