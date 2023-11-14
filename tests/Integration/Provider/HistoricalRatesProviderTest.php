<?php

namespace Integration\Provider;

use App\Model\Currency\AbstractCurrency;
use App\Processor\HistoricalCurrencyProcessor;
use App\Provider\HistoricalRatesProvider;
use PHPUnit\Framework\TestCase;

class HistoricalRatesProviderTest extends TestCase
{
    private $currencies;
    private $provider;
    private $processor;

    public function setUp(): void
    {
        parent::setUp();

        $this->currencies = [
            'USD' => new class() extends AbstractCurrency {
                public $purchaseRate;
                public $sellRate;
                public $name;

                public function setPurchaseRate(float $purchaseRate): void
                {
                    $this->purchaseRate = $purchaseRate;
                }

                public function setSellRate(float $sellRate): void
                {
                    $this->sellRate = $sellRate;
                }
            },
            'EUR' => new class() extends AbstractCurrency {
                public $purchaseRate;
                public $sellRate;
                public $name;

                public function setPurchaseRate(float $purchaseRate): void
                {
                    $this->purchaseRate = $purchaseRate;
                }

                public function setSellRate(float $sellRate): void
                {
                    $this->sellRate = $sellRate;
                }
            },
        ];

        $this->processor = new HistoricalCurrencyProcessor();
        $this->provider = new HistoricalRatesProvider();
    }

    public function testProvide(): void
    {

        $this->provider->setCurrencies($this->currencies);
        $this->provider->setProcessor($this->processor);

        $currencies = $this->provider->provide([
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
                [
                    'no' => '007/A/NBP/2020',
                    'effectiveDate' => '2020-01-10',
                    'mid' => 3.7886,
                ],
                [
                    'no' => '008/A/NBP/2020',
                    'effectiveDate' => '2020-01-13',
                    'mid' => 3.7885,
                ],
                [
                    'no' => '009/A/NBP/2020',
                    'effectiveDate' => '2020-01-14',
                    'mid' => 3.7884,
                ],
                [
                    'no' => '010/A/NBP/2020',
                    'effectiveDate' => '2020-01-15',
                    'mid' => 3.7883,
                ],
                [
                    'no' => '011/A/NBP/2020',
                    'effectiveDate' => '2020-01-16',
                    'mid' => 3.7882,
                ],
            ]
        ]);

        $currency = reset($currencies);

        $this->assertEquals('dolar amerykański', $currency->name);
        $this->assertCount(11, $currency->getHistory());

        $this->assertEquals(3.7882, $currency->getHistory()[0]->getPurchaseRate());
        $this->assertEquals(3.7882, $currency->getHistory()[0]->getSellRate());
        $this->assertTrue($currency->getHistory()[0]->getIsLowest());

        $this->assertEquals(3.7892, $currency->getHistory()[10]->getPurchaseRate());
        $this->assertEquals(3.7892, $currency->getHistory()[10]->getSellRate());
        $this->assertTrue($currency->getHistory()[10]->getIsHighest());
    }
}