<?php

namespace App\Helper;

class NbpApiHelper
{
    private $apiTable;

    public function __construct()
    {
        $this->apiTable = $_ENV['NBP_API_TABLE'];
    }

    public function getApiUrl(): string
    {
        return sprintf('tables/%s', $this->apiTable);
    }

    public function getApiUrlWithCode(string $code): string
    {
        return sprintf('rates/%s/%s', $this->apiTable, $code);
    }

    public function getApiUrlWithDate(string $date): string
    {
        return sprintf('tables/%s/%s', $this->apiTable, $date);
    }

    public function getApiUrlWithDateRangeAndCode(string $code, string $startDate, string $endDate): string
    {
        return sprintf('rates/%s/%s/%s/%s', $this->apiTable, $code, $startDate, $endDate);
    }
}