<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\CurrencyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ExchangeRatesController extends AbstractController
{
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function exchangeRateIndex(): Response
    {
        try {
            $currencies = $this->currencyService->fetchCurrencies();

            $responseContent = [
                'currencies' => $currencies
            ];

            return new JsonResponse($responseContent, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function exchangeRateIndexWithDate(string $date): Response
    {
        try {
            $currencies = $this->currencyService->fetchCurrenciesWithDate($date);

            $responseContent = [
                'currencies' => $currencies
            ];

            return new JsonResponse($responseContent, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function exchangeRateHistory(string $code, string $date): Response
    {
        try {
            $currency = $this->currencyService->fetchCurrencyHistory($code, $date);

            return new JsonResponse($currency, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }
}
