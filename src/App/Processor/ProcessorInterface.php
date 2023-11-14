<?php

namespace App\Processor;

use App\Model\Currency\CurrencyInterface;

interface ProcessorInterface
{
    public static function support(): string;

    public function process(CurrencyInterface $currency, array $currencyData): void;
}