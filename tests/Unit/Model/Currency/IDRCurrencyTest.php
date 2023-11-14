<?php

namespace Unit\Model\Currency;

use App\Model\Currency\IDRCurrency;
use PHPUnit\Framework\TestCase;

class IDRCurrencyTest extends TestCase
{
    public function testGetCode(): void
    {
        $this->assertEquals('IDR', IDRCurrency::getCode());
    }

    public function testGetName(): void
    {
        $currency = new IDRCurrency();
        $currency->setName('Indonesian rupiah');

        $this->assertEquals('Indonesian rupiah', $currency->getName());
    }

    public function testGetExchangeRate(): void
    {
        $currency = new IDRCurrency();
        $currency->setExchangeRate(4.0);

        $this->assertEquals(4.0, $currency->getExchangeRate());
    }

    public function testIsPurchasable(): void
    {
        $currency = new IDRCurrency();

        $this->assertFalse($currency->isPurchasable());
    }

    public function testGetPurchaseRate(): void
    {
        $currency = new IDRCurrency();
        $currency->setPurchaseRate(0.0);

        $this->assertEquals(0.0, $currency->getPurchaseRate());
    }

    public function testIsSellable(): void
    {
        $currency = new IDRCurrency();

        $this->assertTrue($currency->isSellable());
    }

    public function testGetSellRate(): void
    {
        $currency = new IDRCurrency();
        $currency->setSellRate(0.0);

        $this->assertEquals(0.15, $currency->getSellRate());
    }
}