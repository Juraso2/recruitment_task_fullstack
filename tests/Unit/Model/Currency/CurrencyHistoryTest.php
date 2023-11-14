<?php

namespace Unit\Model\Currency;

use App\Model\Currency\CurrencyHistory;
use PHPUnit\Framework\TestCase;

class CurrencyHistoryTest extends TestCase
{
    public function testCurrencyHistory(): void
    {
        $currencyHistory = new CurrencyHistory('2020-01-01', 3.8);

        $this->assertEquals('2020-01-01', $currencyHistory->getDate());
        $this->assertEquals(3.8, $currencyHistory->getExchangeRate());
    }

    public function testCurrencyHistoryPurchaseRate(): void
    {
        $currencyHistory = new CurrencyHistory('2020-01-01', 3.8);
        $currencyHistory->setPurchaseRate(3.9, 0.1);

        $this->assertEquals(4.0, $currencyHistory->getPurchaseRate());
    }

    public function testCurrencyHistorySellRate(): void
    {
        $currencyHistory = new CurrencyHistory('2020-01-01', 3.8);
        $currencyHistory->setSellRate(3.9, 0.1);

        $this->assertEquals(4.0, $currencyHistory->getSellRate());
    }

    public function testCurrencyHistoryIsHighest(): void
    {
        $currencyHistory = new CurrencyHistory('2020-01-01', 3.8);
        $currencyHistory->setIsHighest(true);

        $this->assertTrue($currencyHistory->getIsHighest());
    }

    public function testCurrencyHistoryIsLowest(): void
    {
        $currencyHistory = new CurrencyHistory('2020-01-01', 3.8);
        $currencyHistory->setIsLowest(true);

        $this->assertTrue($currencyHistory->getIsLowest());
    }

    public function testCurrencyHistoryJsonSerialize(): void
    {
        $currencyHistory = new CurrencyHistory('2020-01-01', 3.8);
        $currencyHistory->setPurchaseRate(3.9, 0.1);
        $currencyHistory->setSellRate(3.9, 0.1);
        $currencyHistory->setIsHighest(true);
        $currencyHistory->setIsLowest(true);

        $this->assertEquals([
            'date' => '2020-01-01',
            'exchangeRate' => 3.8,
            'purchaseRate' => 4.0,
            'sellRate' => 4.0,
            'isHighest' => true,
            'isLowest' => true,
        ], $currencyHistory->jsonSerialize());
    }
}