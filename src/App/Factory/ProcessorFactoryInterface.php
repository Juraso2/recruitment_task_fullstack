<?php

namespace App\Factory;

use App\Processor\ProcessorInterface;

interface ProcessorFactoryInterface
{
    public function create(string $type): ProcessorInterface;
}