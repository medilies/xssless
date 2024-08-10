<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\ConfigInterface;

class DompurifyCliConfig implements ConfigInterface
{
    private readonly string $class;

    public function __construct(
        public string $node,
        public string $npm,
    ) {
        $this->class = DompurifyCli::class;
    }

    public function getClass(): string
    {
        return $this->class;
    }
}
