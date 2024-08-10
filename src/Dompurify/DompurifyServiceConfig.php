<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\ConfigInterface;

class DompurifyServiceConfig implements ConfigInterface
{
    public readonly string $class;

    public function __construct(
        public string $node = 'node',
        public string $npm = 'npm',
        public string $host = '127.0.0.1',
        public int $port = 6300,
        public ?string $binary = null,
    ) {
        $this->class = DompurifyService::class;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
