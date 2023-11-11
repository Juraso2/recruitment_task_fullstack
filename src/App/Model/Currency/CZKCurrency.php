<?php

namespace App\Model\Currency;

use App\Helper\NumberHelper;

class CZKCurrency extends abstractCurrency
{
    protected const CODE = 'CZK';
    protected const PURCHASE_RATE = 0.00;
    protected const SELL_RATE = 0.15;

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