<?php

namespace Application\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesHistoryTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/history/USD/2023-11-10');
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('code', $responseData);
        $this->assertEquals('USD', $responseData['code']);
    }

    public function testResponseWillThrowWrongDateException(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/history/USD/9999-99-99');
        self::assertResponseStatusCodeSame(400);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Provided date "9999-99-99" is not valid', $responseData['message']);
    }

    public function testResponseWillThrowNotFoundException(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/history/USD/0000-01-01');
        self::assertResponseStatusCodeSame(404);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Currency "USD" history for date "0000-01-01" not found', $responseData['message']);
    }

    public function testResponseHasValidCurrencyWithHistory(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/history/USD/2023-11-10');
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertEquals('USD', $responseData['code']);
        $this->assertNotEmpty($responseData['history']);
    }
}