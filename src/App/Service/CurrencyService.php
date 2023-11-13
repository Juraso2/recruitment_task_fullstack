<?php

namespace App\Service;

use App\Exception\CurrencyHistoryNotFoundException;
use App\Exception\CurrencyNotSupportedException;
use App\Exception\RatesNotFoundException;
use App\Exception\RatesWithDateNotFoundException;
use App\Exception\WrongDateException;
use App\Helper\NbpApiHelper;
use App\Http\ApiClientInterface;
use App\Model\Currency\CurrencyHistory;
use App\Model\Currency\CurrencyInterface;

class CurrencyService
{
    /**
     * @var CurrencyInterface[]
     */
    private $currencies;
    /**
     * @var NbpApiHelper
     */
    private $nbpApiHelper;
    /**
     * @var ApiClientInterface
     */
    private $apiClient;

    public function __construct(
        iterable           $currencies,
        NbpApiHelper       $nbpApiHelper,
        ApiClientInterface $apiClient
    )
    {
        $this->currencies = $currencies instanceof \Traversable ? iterator_to_array($currencies) : $currencies;
        $this->nbpApiHelper = $nbpApiHelper;
        $this->apiClient = $apiClient;
    }

    /**
     * @throws CurrencyNotSupportedException
     */
    public function fetchCurrency(string $code): CurrencyInterface
    {
        if (!isset($this->currencies[$code])) {
            throw new CurrencyNotSupportedException($code);
        }

        $apiUrl = $this->nbpApiHelper->getApiUrlWithCode($code);
        $response = $this->apiClient->fetch($apiUrl);
        $currency = $this->currencies[$code];

        $this->prepareSingleCurrency($currency, $response);

        return $currency;
    }

    /**
     * @throws RatesNotFoundException
     */
    public function fetchCurrencies(): array
    {
        try {
            $apiUrl = $this->nbpApiHelper->getApiUrl();
            $response = $this->apiClient->fetch($apiUrl);

            $currencies = $this->iterateRates($response[0]['rates']);
        } catch (\Exception $e) {
            throw new RatesNotFoundException();
        }

        return $currencies;
    }

    /**
     * @throws RatesWithDateNotFoundException
     */
    public function fetchCurrenciesWithDate(string $date): array
    {
        try {
            $apiUrl = $this->nbpApiHelper->getApiUrlWithDate($date);
            $response = $this->apiClient->fetch($apiUrl);

            $currencies = $this->iterateRates($response[0]['rates']);
        } catch (\Exception $e) {
            throw new RatesWithDateNotFoundException($date);
        }

        return $currencies;
    }

    /**
     * @throws WrongDateException
     * @throws CurrencyHistoryNotFoundException
     */
    public function fetchCurrencyHistory(string $code, string $endDate): ?CurrencyInterface
    {
        try {
            $date = new \DateTime($endDate);
            $endingDate = $date->format('Y-m-d');
            $startingDate = $date->modify('-15 day')->format('Y-m-d');
        } catch (\Exception $e) {
            throw new WrongDateException($endDate);
        }

        try {
            $apiUrl = $this->nbpApiHelper->getApiUrlWithDateRangeAndCode($code, $startingDate, $endingDate);
            $response = $this->apiClient->fetch($apiUrl);

            $currency = $this->iterateHistoricalRates($response);
        } catch (\Exception $e) {
            throw new CurrencyHistoryNotFoundException($code, $endDate);
        }

        return $currency;
    }

    private function iterateHistoricalRates($history): ?CurrencyInterface
    {
        if (!array_key_exists($history['code'], $this->currencies)) {
            return null;
        }

        $currency = $this->currencies[$history['code']];

        $this->prepareCurrencyHistory($currency, $history);

        return $currency;
    }

    private function iterateRates($rates): array
    {
        $currencies = [];

        foreach ($rates as $currencyData) {
            if (!array_key_exists($currencyData['code'], $this->currencies)) {
                continue;
            }

            $currency = $this->currencies[$currencyData['code']];

            $this->prepareCurrency($currency, $currencyData);

            $currencies[] = $currency;
        }

        return $currencies;
    }

    private function prepareCurrencyHistory(CurrencyInterface $currency, array $currencyData): void
    {
        $currency->setName($currencyData['currency']);

        $rates = array_column($currencyData['rates'], 'effectiveDate');
        array_multisort($rates, SORT_DESC, $currencyData['rates']);

        $highest = array_filter($currencyData['rates'], static function($rate) use($currencyData) {
            return $rate['mid'] === max(array_column($currencyData['rates'], 'mid'));
        });
        $lowest = array_filter($currencyData['rates'], static function($rate) use($currencyData) {
            return $rate['mid'] === min(array_column($currencyData['rates'], 'mid'));
        });

        foreach ($currencyData['rates'] as $rate) {
            $currencyHistory = new CurrencyHistory($rate['effectiveDate'], $rate['mid']);
            $currencyHistory->setPurchaseRate($rate['mid'], $currency::PURCHASE_RATE);
            $currencyHistory->setSellRate($rate['mid'], $currency::SELL_RATE);
            $currencyHistory->setIsHighest($rate['no'] === reset($highest)['no']);
            $currencyHistory->setIsLowest($rate['no'] === reset($lowest)['no']);

            $currency->addHistory($currencyHistory);
        }

    }

    private function prepareCurrency(CurrencyInterface $currency, array $currencyData): void
    {
        $currency->setName($currencyData['currency']);
        $currency->setExchangeRate($currencyData['mid']);
        $currency->setSellRate($currencyData['mid']);
        $currency->setPurchaseRate($currencyData['mid']);
    }

    private function prepareSingleCurrency(CurrencyInterface $currency, array $currencyData): void
    {
        $currency->setName($currencyData['currency']);
        $currency->setExchangeRate($currencyData['rates'][0]['mid']);
        $currency->setSellRate($currencyData['rates'][0]['mid']);
        $currency->setPurchaseRate($currencyData['rates'][0]['mid']);
    }
}