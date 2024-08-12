<?php

namespace Medilies\Xssless\Interfaces;

interface ConfigurableInterface
{
    public function configure(ConfigInterface $config): static;
}
