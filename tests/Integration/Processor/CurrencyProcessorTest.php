<?php

namespace Integration\Processor;

use App\Model\Currency\AbstractCurrency;
use App\Processor\CurrencyProcessor;
use PHPUnit\Framework\TestCase;

class CurrencyProcessorTest extends TestCase
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
            'mid' => 3.7892,
        ];
    }

    public function testProcessWillSetCurrencyName(): void
    {
        (new CurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertEquals('dolar amerykański', $this->currency->getName());
    }

    public function testProcessWillSetCurrencyExchangeRate(): void
    {
        (new CurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertEquals(3.7892, $this->currency->getExchangeRate());
    }

    public function testProcessWillSetCurrencySellRate(): void
    {
        (new CurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertEquals(3.7892, $this->currency->getSellRate());
    }

    public function testProcessWillSetCurrencyPurchaseRate(): void
    {
        (new CurrencyProcessor())->process($this->currency, $this->currencyData);

        $this->assertEquals(3.7892, $this->currency->getPurchaseRate());
    }
}