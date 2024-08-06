<?php

namespace Medilies\Xssless;

interface ConfigurableInterface
{
    public function configure(ConfigInterface $config): static;
}
