<?php

namespace Unit\Model\Currency;

use App\Model\Currency\CZKCurrency;
use PHPUnit\Framework\TestCase;

class CZKCurrencyTest extends TestCase
{
    public function testGetCode(): void
    {
        $this->assertEquals('CZK', CZKCurrency::getCode());
    }

    public function testGetName(): void
    {
        $currency = new CZKCurrency();
        $currency->setName('Czech korona');

        $this->assertEquals('Czech korona', $currency->getName());
    }

    public function testGetExchangeRate(): void
    {
        $currency = new CZKCurrency();
        $currency->setExchangeRate(4.0);

        $this->assertEquals(4.0, $currency->getExchangeRate());
    }

    public function testIsPurchasable(): void
    {
        $currency = new CZKCurrency();

        $this->assertFalse($currency->isPurchasable());
    }

    public function testGetPurchaseRate(): void
    {
        $currency = new CZKCurrency();
        $currency->setPurchaseRate(0.0);

        $this->assertEquals(0.0, $currency->getPurchaseRate());
    }

    public function testIsSellable(): void
    {
        $currency = new CZKCurrency();

        $this->assertTrue($currency->isSellable());
    }

    public function testGetSellRate(): void
    {
        $currency = new CZKCurrency();
        $currency->setSellRate(0.0);

        $this->assertEquals(0.15, $currency->getSellRate());
    }
}