<?php

namespace Unit\Model\Currency;

use App\Model\Currency\EURCurrency;
use PHPUnit\Framework\TestCase;

class EURCurrencyTest extends TestCase
{
    public function testGetCode(): void
    {
        $this->assertEquals('EUR', EURCurrency::getCode());
    }

    public function testGetName(): void
    {
        $currency = new EURCurrency();
        $currency->setName('Euro');

        $this->assertEquals('Euro', $currency->getName());
    }

    public function testGetExchangeRate(): void
    {
        $currency = new EURCurrency();
        $currency->setExchangeRate(4.0);

        $this->assertEquals(4.0, $currency->getExchangeRate());
    }

    public function testIsPurchasable(): void
    {
        $currency = new EURCurrency();

        $this->assertTrue($currency->isPurchasable());
    }

    public function testGetPurchaseRate(): void
    {
        $currency = new EURCurrency();
        $currency->setPurchaseRate(0.0);

        $this->assertEquals(-0.05, $currency->getPurchaseRate());
    }

    public function testIsSellable(): void
    {
        $currency = new EURCurrency();

        $this->assertTrue($currency->isSellable());
    }

    public function testGetSellRate(): void
    {
        $currency = new EURCurrency();
        $currency->setSellRate(0.0);

        $this->assertEquals(0.07, $currency->getSellRate());
    }
}