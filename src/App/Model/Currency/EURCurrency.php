<?php

namespace App\Model\Currency;

use App\Helper\NumberHelper;

class EURCurrency extends AbstractCurrency
{
    protected const CODE = 'EUR';
    public const PURCHASE_RATE = -0.05;
    public const SELL_RATE = 0.07;

    public function isPurchasable(): bool
    {
        return true;
    }

    public function isSellable(): bool
    {
        return true;
    }

    public function setPurchaseRate(float $purchaseRate): void
    {
        $this->purchaseRate = NumberHelper::sumFloats($purchaseRate, self::PURCHASE_RATE);
    }

    public function setSellRate(float $sellRate): void
    {
        $this->sellRate = NumberHelper::sumFloats($sellRate, self::SELL_RATE);
    }
}