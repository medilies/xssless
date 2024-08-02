<?php

namespace Medilies\Xssless\Dompurify;

use Illuminate\Support\Facades\Http;
use Medilies\Xssless\ServiceInterface;

class DompurifyService implements ServiceInterface
{
    private string $host;

    private int $port;

    /** @param ?array<string, mixed> $config */
    public function __construct(?array $config = null)
    {
        if (! is_null($config)) {
            $this->configure($config);
        }
    }

    // TODO: depend on Symphony HTTP and Process

    public function start(): void {}

    // TODO: setup and return output

    /** @param ?array<string, mixed> $config */
    public function send(string $html, ?array $config = null): string
    {
        if (! is_null($config)) {
            $this->configure($config);
        }

        $url = "http://{$this->host}:{$this->port}";

        $response = Http::post($url, ['html' => $html]);

        return $response->body();
    }

    /** @param array<string, mixed> $config */
    public function configure(array $config): static
    {
        // TODO validate
        $this->host = $config['host'];
        $this->port = $config['port'];

        return $this;
    }
}
