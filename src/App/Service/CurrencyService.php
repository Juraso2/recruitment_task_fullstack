<?php

namespace App\Service;

use App\Exception\CurrencyNotSupportedException;
use App\Exception\RatesNotFoundException;
use App\Exception\RatesWithDateNotFoundException;
use App\Helper\NbpApiHelper;
use App\Http\ApiClientInterface;
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

    private function iterateRates($rates): array
    {
        $currencies = [];

        foreach ($rates as $currencyData) {
            if(!array_key_exists($currencyData['code'], $this->currencies)) {
                continue;
            }

            $currency = $this->currencies[$currencyData['code']];

            $this->prepareCurrency($currency, $currencyData);

            $currencies[] = $currency;
        }

        return $currencies;
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