<?php

namespace Unit\Model\Currency;

use App\Model\Currency\USDCurrency;
use PHPUnit\Framework\TestCase;

class USDCurrencyTest extends TestCase
{
    public function testGetCode(): void
    {
        $this->assertEquals('USD', USDCurrency::getCode());
    }

    public function testGetName(): void
    {
        $currency = new USDCurrency();
        $currency->setName('US Dollar');

        $this->assertEquals('US Dollar', $currency->getName());
    }

    public function testGetExchangeRate(): void
    {
        $currency = new USDCurrency();
        $currency->setExchangeRate(4.0);

        $this->assertEquals(4.0, $currency->getExchangeRate());
    }

    public function testIsPurchasable(): void
    {
        $currency = new USDCurrency();

        $this->assertTrue($currency->isPurchasable());
    }

    public function testGetPurchaseRate(): void
    {
        $currency = new USDCurrency();
        $currency->setPurchaseRate(0.0);

        $this->assertEquals(-0.05, $currency->getPurchaseRate());
    }

    public function testIsSellable(): void
    {
        $currency = new USDCurrency();

        $this->assertTrue($currency->isSellable());
    }

    public function testGetSellRate(): void
    {
        $currency = new USDCurrency();
        $currency->setSellRate(0.0);

        $this->assertEquals(0.07, $currency->getSellRate());
    }
}