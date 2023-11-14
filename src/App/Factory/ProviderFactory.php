<?php

namespace App\Factory;

use App\Provider\ProviderInterface;

class ProviderFactory
{
    private $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers instanceof \Traversable ? iterator_to_array($providers) : $providers;
    }

    public function create(string $type): ProviderInterface
    {
        if (array_key_exists($type, $this->providers)) {
            return $this->providers[$type];
        }

        throw new \InvalidArgumentException('Iterator not found');
    }
}