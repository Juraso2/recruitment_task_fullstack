<?php

namespace App\Helper;

class NumberHelper
{
    public static function numberOfDecimals(float $number): int
    {
        return (int)strpos(strrev($number), '.');
    }

    public static function sumFloats(float $a, float $b): float
    {
        $aDecimals = self::numberOfDecimals($a);
        $bDecimals = self::numberOfDecimals($b);
        $decimals = max($aDecimals, $bDecimals);

        return round($a + $b, $decimals);
    }
}