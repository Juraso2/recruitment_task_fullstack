<?php

namespace App\Provider;

use App\Processor\ProcessorInterface;

interface ProviderInterface
{
    public static function support(): string;

    public function provide(array $iterable): array;

    public function setCurrencies(array $currencies): void;

    public function setProcessor(ProcessorInterface $processor): void;
}