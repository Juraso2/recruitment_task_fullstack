<?php

namespace Unit\Helper;

use App\Helper\NumberHelper;
use PHPUnit\Framework\TestCase;

class NumberHelperTest extends TestCase
{
    public function testNumberOfDecimals(): void
    {
        $this->assertEquals(0, NumberHelper::numberOfDecimals(1));
        $this->assertEquals(1, NumberHelper::numberOfDecimals(1.1));
        $this->assertEquals(2, NumberHelper::numberOfDecimals(1.11));
        $this->assertEquals(3, NumberHelper::numberOfDecimals(1.111));
    }

    public function testSumFloats(): void
    {
        $this->assertEquals(2, NumberHelper::sumFloats(1, 1));
        $this->assertEquals(2.1, NumberHelper::sumFloats(1.1, 1));
        $this->assertEquals(2.1, NumberHelper::sumFloats(1, 1.1));
        $this->assertEquals(2.2, NumberHelper::sumFloats(1.1, 1.1));
        $this->assertEquals(2.22, NumberHelper::sumFloats(1.11, 1.11));
    }
}