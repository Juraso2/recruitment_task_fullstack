<?php

namespace Unit\Controller;

use App\Controller\ExchangeRatesController;
use App\Exception\WrongDateException;
use App\Model\Currency\AbstractCurrency;
use App\Model\Currency\CurrencyHistoryInterface;
use App\Service\CurrencyService;
use Exception;
use PHPUnit\Framework\TestCase;

class ExchangeRatesControllerTest extends TestCase
{
    public function testExchangeRateIndex(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currencyService->expects($this->once())
            ->method('fetchCurrencies')
            ->willReturn([
                [
                    'currency' => 'dolar amerykański',
                    'code' => 'USD',
                    'rates' => [
                        [
                            'no' => '001/A/NBP/2020',
                            'effectiveDate' => '2020-01-02',
                            'mid' => 3.7892,
                        ],
                    ],
                ],
                [
                    'currency' => 'euro',
                    'code' => 'EUR',
                    'rates' => [
                        [
                            'no' => '001/A/NBP/2020',
                            'effectiveDate' => '2020-01-02',
                            'mid' => 4.2321,
                        ],
                    ],
                ],
            ]);

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateIndex();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            '{"currencies":[{"currency":"dolar ameryka\u0144ski","code":"USD","rates":[{"no":"001\/A\/NBP\/2020","effectiveDate":"2020-01-02","mid":3.7892}]},{"currency":"euro","code":"EUR","rates":[{"no":"001\/A\/NBP\/2020","effectiveDate":"2020-01-02","mid":4.2321}]}]}',
            $response->getContent()
        );
    }

    public function testExchangeRateIndexWillReturnNotFound(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currencyService->expects($this->once())
            ->method('fetchCurrencies')
            ->willThrowException(new Exception('Not found'));

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateIndex();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(
            '{"message":"Not found"}',
            $response->getContent()
        );
    }

    public function testExchangeRateIndexWithDate(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currencyService->expects($this->once())
            ->method('fetchCurrenciesWithDate')
            ->willReturn([
                [
                    'currency' => 'dolar amerykański',
                    'code' => 'USD',
                    'rates' => [
                        [
                            'no' => '001/A/NBP/2020',
                            'effectiveDate' => '2020-01-02',
                            'mid' => 3.7892,
                        ],
                    ],
                ],
                [
                    'currency' => 'euro',
                    'code' => 'EUR',
                    'rates' => [
                        [
                            'no' => '001/A/NBP/2020',
                            'effectiveDate' => '2020-01-02',
                            'mid' => 4.2321,
                        ],
                    ],
                ],
            ]);

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateIndexWithDate('2020-01-02');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            '{"currencies":[{"currency":"dolar ameryka\u0144ski","code":"USD","rates":[{"no":"001\/A\/NBP\/2020","effectiveDate":"2020-01-02","mid":3.7892}]},{"currency":"euro","code":"EUR","rates":[{"no":"001\/A\/NBP\/2020","effectiveDate":"2020-01-02","mid":4.2321}]}]}',
            $response->getContent()
        );
    }

    public function testExchangeRateIndexWithDateWillReturnNotFound(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currencyService->expects($this->once())
            ->method('fetchCurrenciesWithDate')
            ->willThrowException(new Exception('Not found'));

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateIndexWithDate('2020-01-02');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(
            '{"message":"Not found"}',
            $response->getContent()
        );
    }

    public function testExchangeRateHistory(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currency = new class() extends AbstractCurrency {
            protected $code = 'USD';
            protected $name = 'dolar amerykański';
            protected $exchangeRate = 3.7892;
            protected $purchaseRate = 3.7892;
            protected $sellRate = 3.7892;
            protected $history = [
                [
                    'no' => '001/A/NBP/2020',
                    'effectiveDate' => '2020-01-02',
                    'mid' => 3.7892,
                ],
            ];

            public static function getCode(): string
            {
                return 'USD';
            }

            public function getName(): string {
                return 'dolar amerykański';
            }

            public function setName(string $name): void {}

            public function getExchangeRate(): ?float
            {
                return 3.7892;
            }

            public function setExchangeRate(float $exchangeRate): void {}

            public function isPurchasable(): bool
            {
                return true;
            }

            public function getPurchaseRate(): ?float
            {
                return 3.7892;
            }

            public function setPurchaseRate(float $purchaseRate): void {}

            public function isSellable(): bool
            {
                return true;
            }

            public function getSellRate(): ?float
            {
                return 3.7892;
            }

            public function setSellRate(float $sellRate): void {}

            public function addHistory(CurrencyHistoryInterface $currencyHistory): void {}

            public function getHistory(): array
            {
                return [
                    [
                        'no' => '001/A/NBP/2020',
                        'effectiveDate' => '2020-01-02',
                        'mid' => 3.7892,
                    ],
                ];
            }

            public function jsonSerialize(): array
            {
                return [
                    'code' => self::getCode(),
                    'name' => $this->getName(),
                    'exchangeRate' => $this->getExchangeRate(),
                    'isPurchasable' => $this->isPurchasable(),
                    'purchaseRate' => $this->getPurchaseRate(),
                    'isSellable' => $this->isSellable(),
                    'sellRate' => $this->getSellRate(),
                    'history' => $this->getHistory(),
                ];
            }
        };

        $currencyService->expects($this->once())
            ->method('fetchCurrencyHistory')
            ->willReturn($currency);

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateHistory('USD', '2020-01-02');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(
            '{"code":"USD","name":"dolar ameryka\u0144ski","exchangeRate":3.7892,"isPurchasable":true,"purchaseRate":3.7892,"isSellable":true,"sellRate":3.7892,"history":[{"no":"001\/A\/NBP\/2020","effectiveDate":"2020-01-02","mid":3.7892}]}',
            $response->getContent()
        );
    }

    public function testExchangeRateHistoryWillReturnBadRequest(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currencyService->expects($this->once())
            ->method('fetchCurrencyHistory')
            ->willThrowException(new WrongDateException('Wrong date'));

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateHistory('USD', '2020-01-02');

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            '{"message":"Provided date \u0022Wrong date\u0022 is not valid"}',
            $response->getContent()
        );
    }

    public function testExchangeRateHistoryWillReturnNotFound(): void
    {
        $currencyService = $this->createMock(CurrencyService::class);

        $currencyService->expects($this->once())
            ->method('fetchCurrencyHistory')
            ->willThrowException(new Exception('Not found'));

        $controller = new ExchangeRatesController($currencyService);

        $response = $controller->exchangeRateHistory('USD', '2020-01-02');

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(
            '{"message":"Not found"}',
            $response->getContent()
        );
    }
}