<?php

namespace Unit\Model\Currency;

use App\Model\Currency\CurrencyHistory;
use PHPUnit\Framework\TestCase;
use App\Model\Currency\AbstractCurrency;

class AbstractCurrencyTest extends TestCase
{
    private $currency;

    public function setUp(): void
    {
        $this->currency = $this->getMockForAbstractClass(AbstractCurrency::class);

        $this->currency->setName('Test');
        $this->currency->setExchangeRate(1.0);
    }

    public function testGetCode(): void
    {
        $this->assertEquals('', $this->currency::getCode());
    }

    public function testGetName(): void
    {
        $this->assertEquals('Test', $this->currency->getName());
    }

    public function testGetExchangeRate(): void
    {
        $this->currency->setExchangeRate(0.0);

        $this->assertEquals(0.0, $this->currency->getExchangeRate());
    }

    public function testIsPurchasable(): void
    {
        $this->assertFalse($this->currency->isPurchasable());
    }

    public function testGetPurchaseRate(): void
    {
        $this->currency->setPurchaseRate(0.0);

        $this->assertEquals(0.0, $this->currency->getPurchaseRate());
    }

    public function testIsSellable(): void
    {
        $this->assertFalse($this->currency->isSellable());
    }

    public function testGetSellRate(): void
    {
        $this->currency->setSellRate(0.0);

        $this->assertEquals(0.0, $this->currency->getSellRate());
    }

    public function testAddHistory(): void
    {
        $history = new CurrencyHistory('2020-01-01', 1.0);
        $this->currency->addHistory($history);

        $this->assertEquals([$history], $this->currency->getHistory());
    }

    public function testJsonSerialize(): void
    {
        $this->assertEquals(
            [
                'code' => '',
                'name' => 'Test',
                'exchangeRate' => 1.0,
                'isPurchasable' => false,
                'purchaseRate' => null,
                'isSellable' => false,
                'sellRate' => null,
                'history' => [],
            ],
            $this->currency->jsonSerialize()
        );
    }
}