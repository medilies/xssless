<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\Interfaces\ConfigInterface;

class DompurifyServiceConfig implements ConfigInterface
{
    private readonly string $class;

    public function __construct(
        public string $node = 'node',
        public string $npm = 'npm',
        public string $host = '127.0.0.1',
        public int $port = 63000,
        public ?string $binary = null,
        public int $startupTimeoutMs = 6000,
    ) {
        $this->class = DompurifyService::class;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
