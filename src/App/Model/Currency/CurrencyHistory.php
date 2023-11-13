<?php

namespace App\Model\Currency;

use App\Helper\NumberHelper;

class CurrencyHistory implements CurrencyHistoryInterface, \JsonSerializable
{
    private $date;
    private $exchangeRate;
    private $purchaseRate;
    private $sellRate;
    private $isHighest = false;
    private $isLowest = false;

    public function __construct(string $date, float $exchangeRate)
    {
        $this->date = $date;
        $this->exchangeRate = $exchangeRate;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getExchangeRate(): float
    {
        return $this->exchangeRate;
    }

    public function getPurchaseRate(): float
    {
        return $this->purchaseRate;
    }

    public function setPurchaseRate(float $purchaseRate, float $currencyPurchaseRate): void
    {
        $this->purchaseRate = NumberHelper::sumFloats($purchaseRate, $currencyPurchaseRate);
    }

    public function getSellRate(): float
    {
        return $this->sellRate;
    }

    public function setSellRate(float $sellRate, float $currencySellRate): void
    {
        $this->sellRate = NumberHelper::sumFloats($sellRate, $currencySellRate);
    }

    public function setIsHighest(bool $isHighest): void
    {
        $this->isHighest = $isHighest;
    }

    public function getIsHighest(): bool
    {
        return $this->isHighest;
    }

    public function setIsLowest(bool $isLowest): void
    {
        $this->isLowest = $isLowest;
    }

    public function getIsLowest(): bool
    {
        return $this->isLowest;
    }

    public function jsonSerialize(): array
    {
        return [
            'date' => $this->getDate(),
            'exchangeRate' => $this->getExchangeRate(),
            'purchaseRate' => $this->getPurchaseRate(),
            'sellRate' => $this->getSellRate(),
            'isHighest' => $this->getIsHighest(),
            'isLowest' => $this->getIsLowest(),
        ];
    }
}