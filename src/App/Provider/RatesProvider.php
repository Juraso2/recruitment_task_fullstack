<?php

namespace App\Provider;

use App\Enum\CurrencyTypeEnum;
use App\Processor\ProcessorInterface;

class RatesProvider implements ProviderInterface
{
    private $processor;
    private $currencies = [];

    public static function support(): string
    {
        return CurrencyTypeEnum::RATES;
    }

    public function provide(array $iterable): array
    {
        $currencies = [];

        foreach ($iterable as $currencyData) {
            if (!array_key_exists('code', $currencyData)) {
                continue;
            }

            if (!array_key_exists($currencyData['code'], $this->currencies)) {
                continue;
            }

            $currency = $this->currencies[$currencyData['code']];

            $this->processor->process($currency, $currencyData);

            $currencies[] = $currency;
        }

        return $currencies;
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