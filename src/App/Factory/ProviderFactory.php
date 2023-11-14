<?php

namespace App\Factory;

use App\Provider\ProviderInterface;

final class ProviderFactory implements ProviderFactoryInterface
{
    private $providers;

    public function __construct(iterable $providers)
    {
        $this->providers = $providers instanceof \Traversable ? iterator_to_array($providers) : $providers;
    }

    public function create(string $type): ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->support() === $type) {
                return $provider;
            }
        }

        throw new \InvalidArgumentException('Provider not found');
    }
}