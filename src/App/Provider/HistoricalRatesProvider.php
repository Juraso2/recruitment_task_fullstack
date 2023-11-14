<?php

namespace App\Provider;

use App\Enum\CurrencyTypeEnum;
use App\Processor\ProcessorInterface;

class HistoricalRatesProvider implements ProviderInterface
{
    private $processor;
    private $currencies = [];

    public static function support(): string
    {
        return CurrencyTypeEnum::HISTORICAL_RATES;
    }

    public function provide(array $iterable): array
    {
        if (!array_key_exists('code', $iterable)) {
            return [];
        }

        if (!array_key_exists($iterable['code'], $this->currencies)) {
            return [];
        }

        $currency = $this->currencies[$iterable['code']];
        $currency->setName($iterable['currency']);

        $this->processor->process($currency, $iterable);

        return [$currency];
    }

    public function setProcessor(ProcessorInterface $processor): void
    {
        $this->processor = $processor;
    }

    public function setCurrencies(array $currencies): void
    {
        $this->currencies = $currencies;
    }
}