<?php

namespace App\Model\Currency;

abstract class AbstractCurrency implements CurrencyInterface, \JsonSerializable
{
    protected const CODE = '';
    public const PURCHASE_RATE = 0.0;
    public const SELL_RATE = 0.0;

    protected $name;
    protected $exchangeRate;
    protected $purchaseRate;
    protected $sellRate;
    protected $history = [];

    public static function getCode(): string
    {
        return static::CODE;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getExchangeRate(): ?float
    {
        return $this->exchangeRate;
    }

    public function setExchangeRate(float $exchangeRate): void
    {
        $this->exchangeRate = $exchangeRate;
    }

    public function isPurchasable(): bool
    {
        return false;
    }

    public function getPurchaseRate(): ?float
    {
        return $this->purchaseRate;
    }

    public function isSellable(): bool
    {
        return false;
    }

    public function getSellRate(): ?float
    {
        return $this->sellRate;
    }

    public function addHistory(CurrencyHistoryInterface $currencyHistory): void
    {
        $this->history[] = $currencyHistory;
    }

    public function getHistory(): array
    {
        return $this->history;
    }

    public function jsonSerialize(): array
    {
        return [
            'code' => self::getCode(),
            'name' => $this->getName(),
            'exchangeRate' => $this->getExchangeRate(),
            'isPurchasable' => $this->isPurchasable(),
            'purchaseRate' => $this->getPurchaseRate(),
            'isSellable' => $this->isSellable(),
            'sellRate' => $this->getSellRate(),
            'history' => $this->getHistory(),
        ];
    }
}