<?php

namespace Unit\Helper;

use App\Helper\NbpApiHelper;
use PHPUnit\Framework\TestCase;

class NbpApiHelperTest extends TestCase
{
    private $nbpApiHelper;

    public function setUp(): void
    {
        $this->nbpApiHelper = new NbpApiHelper();
    }

    public function testGetApiUrl(): void
    {
        $this->assertEquals('tables/A', $this->nbpApiHelper->getApiUrl());
    }

    public function testGetApiUrlWithDate(): void
    {
        $this->assertEquals('tables/A/2020-01-01', $this->nbpApiHelper->getApiUrlWithDate('2020-01-01'));
    }

    public function testGetApiUrlWithDateRangeAndCode(): void
    {
        $this->assertEquals('rates/A/USD/2020-01-01/2020-01-02', $this->nbpApiHelper->getApiUrlWithDateRangeAndCode('USD', '2020-01-01', '2020-01-02'));
    }
}