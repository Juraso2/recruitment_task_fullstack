<?php

namespace App\Model\Currency;

interface CurrencyHistoryInterface
{
    public function getDate(): string;

    public function getExchangeRate(): float;

    public function getPurchaseRate(): float;

    public function setPurchaseRate(float $purchaseRate, float $currencyPurchaseRate): void;

    public function getSellRate(): float;

    public function setSellRate(float $sellRate, float $currencySellRate): void;

    public function setIsHighest(bool $isHighest): void;

    public function getIsHighest(): bool;

    public function setIsLowest(bool $isLowest): void;

    public function getIsLowest(): bool;
}