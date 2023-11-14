<?php

namespace App\Factory;

use App\Processor\ProcessorInterface;
use InvalidArgumentException;
use Traversable;

class ProcessorFactory
{
    private $processors;

    public function __construct(iterable $processors)
    {
        $this->processors = $processors instanceof Traversable ? iterator_to_array($processors) : $processors;
    }

    public function create(string $type): ProcessorInterface
    {
        foreach ($this->processors as $processor) {
            if ($processor->support() === $type) {
                return $processor;
            }
        }

        throw new InvalidArgumentException('Processor not found');
    }
}