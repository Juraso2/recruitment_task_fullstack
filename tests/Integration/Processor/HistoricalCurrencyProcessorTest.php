<?php

namespace Integration\Processor;

use App\Model\Currency\AbstractCurrency;
use App\Processor\HistoricalCurrencyProcessor;
use PHPUnit\Framework\TestCase;

class HistoricalCurrencyProcessorTest extends TestCase
{
    private $currency;
    private $currencyData;

    public function setUp(): void
    {
        parent::setUp();

        $this->currency = new class() extends AbstractCurrency {
            public $purchaseRate;
            public $sellRate;

            public function setPurchaseRate(float $purchaseRate): void
            {
                $this->purchaseRate = $purchaseRate;
            }

            public function setSellRate(float $sellRate): void
            {
                $this->sellRate = $sellRate;
            }
        };

        $this->currencyData = [
            'currency' => 'dolar amerykański',
            'code' => 'USD',
            'rates' => [
                [
                    'no' => '001/A/NBP/2020',
                    'effectiveDate' => '2020-01-02',
                    'mid' => 3.7892,
                ],
                [
                    'no' => '002/A/NBP/2020',
                    'effectiveDate' => '2020-01-03',
                    'mid' => 3.7891,
                ],
                [
                    'no' => '003/A/NBP/2020',
                    'effectiveDate' => '2020-01-06',
                    'mid' => 3.7890,
                ],
                [
                    'no' => '004/A/NBP/2020',
                    'effectiveDate' => '2020-01-07',
                    'mid' => 3.7889,
                ],
                [
                    'no' => '005/A/NBP/2020',
                    'effectiveDate' => '2020-01-08',
                    'mid' => 3.7888,
                ],
                [
                    'no' => '006/A/NBP/2020',
                    'effectiveDate' => '2020-01-09',
                    'mid' => 3.7887,
                ],
            ]
        ];
    }

    public function testProcessWillSetCurrencyHistory(): void
    {
        (new HistoricalCurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertCount(6, $this->currency->getHistory());
    }

    public function testProcessWillSetPurchaseRate(): void
    {
        (new HistoricalCurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertEquals(3.7887, $this->currency->getHistory()[0]->getPurchaseRate());
    }

    public function testProcessWillSetSellRate(): void
    {
        (new HistoricalCurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertEquals(3.7887, $this->currency->getHistory()[0]->getSellRate());
    }

    public function testProcessWillSetIsHighest(): void
    {
        (new HistoricalCurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertFalse($this->currency->getHistory()[0]->getIsHighest());
        $this->assertTrue($this->currency->getHistory()[5]->getIsHighest());
    }

    public function testProcessWillSetIsLowest(): void
    {
        (new HistoricalCurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertTrue($this->currency->getHistory()[0]->getIsLowest());
        $this->assertFalse($this->currency->getHistory()[5]->getIsLowest());
    }
}