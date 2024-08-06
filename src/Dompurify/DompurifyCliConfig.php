<?php

namespace Medilies\Xssless\Dompurify;

use Medilies\Xssless\ConfigInterface;
use Medilies\Xssless\XsslessException;

class DompurifyCliConfig implements ConfigInterface
{
    private readonly string $class;

    private string $nodePath;

    private string $npmPath;

    public function __construct(
        mixed $nodePath,
        mixed $npmPath,
    ) {
        $this->class = DompurifyCli::class;
        $this->setNodePath($nodePath);
        $this->setNpmPath($npmPath);
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
}
