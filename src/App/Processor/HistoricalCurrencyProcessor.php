<?php

namespace App\Processor;

use App\Enum\CurrencyTypeEnum;
use App\Model\Currency\CurrencyHistory;
use App\Model\Currency\CurrencyInterface;

class HistoricalCurrencyProcessor implements ProcessorInterface
{
    public static function support(): string
    {
        return CurrencyTypeEnum::HISTORICAL_RATES;
    }

    public function process(CurrencyInterface $currency, array $currencyData): void
    {
        $rates = array_column($currencyData['rates'], 'effectiveDate');
        array_multisort($rates, SORT_DESC, $currencyData['rates']);

        $highest = array_filter($currencyData['rates'], static function($rate) use($currencyData) {
            return $rate['mid'] === max(array_column($currencyData['rates'], 'mid'));
        });
        $lowest = array_filter($currencyData['rates'], static function($rate) use($currencyData) {
            return $rate['mid'] === min(array_column($currencyData['rates'], 'mid'));
        });

        foreach ($currencyData['rates'] as $rate) {
            $currencyHistory = new CurrencyHistory($rate['effectiveDate'], $rate['mid']);
            $currencyHistory->setPurchaseRate($rate['mid'], $currency::PURCHASE_RATE);
            $currencyHistory->setSellRate($rate['mid'], $currency::SELL_RATE);
            $currencyHistory->setIsHighest($rate['no'] === reset($highest)['no']);
            $currencyHistory->setIsLowest($rate['no'] === reset($lowest)['no']);

            $currency->addHistory($currencyHistory);
        }
    }
}