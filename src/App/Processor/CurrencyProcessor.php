<?php

namespace App\Processor;

use App\Enum\CurrencyTypeEnum;
use App\Model\Currency\CurrencyInterface;

class CurrencyProcessor implements ProcessorInterface
{
    public static function support(): string
    {
        return CurrencyTypeEnum::RATES;
    }

    public function process(CurrencyInterface $currency, array $currencyData): void
    {
        $currency->setName($currencyData['currency']);
        $currency->setExchangeRate($currencyData['mid']);
        $currency->setSellRate($currencyData['mid']);
        $currency->setPurchaseRate($currencyData['mid']);
    }
}