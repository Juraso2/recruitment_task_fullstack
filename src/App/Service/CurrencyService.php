<?php

namespace App\Service;

use App\Enum\CurrencyTypeEnum;
use App\Exception\CurrencyHistoryNotFoundException;
use App\Exception\CurrencyNotSupportedException;
use App\Exception\RatesNotFoundException;
use App\Exception\RatesWithDateNotFoundException;
use App\Exception\WrongDateException;
use App\Factory\ProviderFactory;
use App\Factory\ProcessorFactory;
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
    /**
     * @var ProviderFactory
     */
    private $providerFactory;
    /**
     * @var ProcessorFactory
     */
    private $processorFactory;

    public function __construct(
        iterable           $currencies,
        NbpApiHelper       $nbpApiHelper,
        ApiClientInterface $apiClient,
        ProviderFactory    $providerFactory,
        ProcessorFactory   $processorFactory
    )
    {
        $this->currencies = $currencies instanceof \Traversable ? iterator_to_array($currencies) : $currencies;
        $this->nbpApiHelper = $nbpApiHelper;
        $this->apiClient = $apiClient;
        $this->providerFactory = $providerFactory;
        $this->processorFactory = $processorFactory;
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
        $processor = $this->processorFactory->create(CurrencyTypeEnum::HISTORICAL_RATES);
        $provider = $this->providerFactory->create(CurrencyTypeEnum::HISTORICAL_RATES);
        $provider->setCurrencies($this->currencies);
        $provider->setProcessor($processor);

        $result = $provider->provide($history);

        return reset($result);
    }

    private function iterateRates($rates): array
    {
        $processor = $this->processorFactory->create(CurrencyTypeEnum::RATES);
        $provider = $this->providerFactory->create(CurrencyTypeEnum::RATES);
        $provider->setCurrencies($this->currencies);
        $provider->setProcessor($processor);

        return $provider->provide($rates);
    }
}