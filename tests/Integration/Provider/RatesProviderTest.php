<?php

namespace Integration\Provider;

use App\Model\Currency\AbstractCurrency;
use App\Processor\CurrencyProcessor;
use App\Provider\RatesProvider;
use PHPUnit\Framework\TestCase;

class RatesProviderTest extends TestCase
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

        $this->processor = new CurrencyProcessor();
        $this->provider = new RatesProvider();
    }

    public function testProvide(): void
    {

        $this->provider->setCurrencies($this->currencies);
        $this->provider->setProcessor($this->processor);

        $currencies = $this->provider->provide([
            [
                'currency' => 'dolar amerykański',
                'code' => 'USD',
                'mid' => 3.7892,
            ],
            [
                'currency' => 'euro',
                'code' => 'EUR',
                'mid' => 4.2321,
            ],
        ]);

        $this->assertCount(2, $currencies);

        $this->assertEquals('dolar amerykański', $currencies[0]->name);
        $this->assertEquals(3.7892, $currencies[0]->purchaseRate);
        $this->assertEquals(3.7892, $currencies[0]->sellRate);

        $this->assertEquals('euro', $currencies[1]->name);
        $this->assertEquals(4.2321, $currencies[1]->purchaseRate);
        $this->assertEquals(4.2321, $currencies[1]->sellRate);
    }
}