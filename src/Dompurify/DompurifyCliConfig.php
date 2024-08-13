<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\Interfaces\ConfigInterface;

class DompurifyCliConfig implements ConfigInterface
{
    private readonly string $class;

    public function __construct(
        public string $node = 'node',
        public string $npm = 'npm',
        public ?string $binary = null,
        public ?string $tempFolder = null,
    ) {
        $this->class = DompurifyCli::class;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Must be implemented for Laravel config cache
     */
    public static function __set_state(array $state_array): static
    {
        return new static(...array_intersect_key($state_array, [
            'node' => true,
            'npm' => true,
            'binary' => true,
            'tempFolder' => true,
        ]));
    }
}
