<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\ConfigInterface;

class DompurifyServiceConfig implements ConfigInterface
{
    public readonly string $class;

    public function __construct(
        public string $node,
        public string $npm,
        public string $host,
        public int $port,
    ) {
        $this->class = DompurifyService::class;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
