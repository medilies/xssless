<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\ConfigInterface;
use Medilies\Xssless\XsslessException;

class DompurifyServiceConfig implements ConfigInterface
{
    public string $class;

    public string $nodePath;

    public string $npmPath;

    public string $host;

    public int $port;

    // TODO: use type guard
    public function __construct(
        mixed $nodePath,
        mixed $npmPath,
        mixed $host,
        mixed $port,
    ) {
        $this->class = DompurifyService::class;
        $this->setNodePath($nodePath);
        $this->setNpmPath($npmPath);
        $this->setHost($host);
        $this->setPort($port);
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setNodePath(mixed $value): static
    {
        if (! is_string($value)) {
            throw new XsslessException('nodePath must be a string.');
        }

        $this->nodePath = $value;

        return $this;
    }

    public function setHost(mixed $value): static
    {
        if (! is_string($value)) {
            throw new XsslessException('host must be a string.');
        }

        $this->host = $value;

        return $this;
    }

    public function setPort(mixed $value): static
    {
        if (! is_int($value)) {
            throw new XsslessException('host must be a string.');
        }

        $this->port = $value;

        return $this;
    }

    public function setNpmPath(mixed $value): static
    {
        if (! is_string($value)) {
            throw new XsslessException('npmPath must be a string.');
        }

        $this->npmPath = $value;

        return $this;
    }

    public function getNodePath(): string
    {
        return $this->nodePath;
    }

    public function getNpmPath(): string
    {
        return $this->npmPath;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
