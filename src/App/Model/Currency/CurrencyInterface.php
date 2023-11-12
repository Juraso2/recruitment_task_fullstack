<?php

namespace App\Model\Currency;

interface CurrencyInterface
{
    public static function getCode(): string;

    public function getName(): string;

    public function setName(string $name): void;

    public function getExchangeRate(): ?float;

    public function setExchangeRate(float $exchangeRate): void;

    public function isPurchasable(): bool;

    public function getPurchaseRate(): ?float;

    public function setPurchaseRate(float $purchaseRate): void;

    public function isSellable(): bool;

    public function getSellRate(): ?float;

    public function setSellRate(float $sellRate): void;

    public function addHistory(CurrencyHistoryInterface $currencyHistory): void;

    public function getHistory(): array;
}