<?php

namespace Unit\Model\Currency;

use App\Model\Currency\BRLCurrency;
use PHPUnit\Framework\TestCase;

class BRLCurrencyTest extends TestCase
{
    public function testGetCode(): void
    {
        $this->assertEquals('BRL', BRLCurrency::getCode());
    }

    public function testGetName(): void
    {
        $currency = new BRLCurrency();
        $currency->setName('Brazilian Real');

        $this->assertEquals('Brazilian Real', $currency->getName());
    }

    public function testGetExchangeRate(): void
    {
        $currency = new BRLCurrency();
        $currency->setExchangeRate(4.0);

        $this->assertEquals(4.0, $currency->getExchangeRate());
    }

    public function testIsPurchasable(): void
    {
        $currency = new BRLCurrency();

        $this->assertFalse($currency->isPurchasable());
    }

    public function testGetPurchaseRate(): void
    {
        $currency = new BRLCurrency();
        $currency->setPurchaseRate(0.0);

        $this->assertEquals(0.0, $currency->getPurchaseRate());
    }

    public function testIsSellable(): void
    {
        $currency = new BRLCurrency();

        $this->assertTrue($currency->isSellable());
    }

    public function testGetSellRate(): void
    {
        $currency = new BRLCurrency();
        $currency->setSellRate(0.0);

        $this->assertEquals(0.15, $currency->getSellRate());
    }
}