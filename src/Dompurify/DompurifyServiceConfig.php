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

    /**
     * Must be implemented for Laravel config cache
     */
    public static function __set_state(array $state_array): static
	{
		return new static(...array_intersect_key($state_array, [
            'node' => true,
            'npm' => true,
            'host' => true,
            'port' => true,
            'binary' => true,
        ]));
	}
}
