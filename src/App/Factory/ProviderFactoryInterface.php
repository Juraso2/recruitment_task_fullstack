<?php

namespace App\Factory;

use App\Provider\ProviderInterface;

interface ProviderFactoryInterface
{
    public function create(string $type): ProviderInterface;
}