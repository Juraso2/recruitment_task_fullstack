<?php

namespace Application\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesWithDateTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2023-11-10');
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('currencies', $responseData);
    }

    public function testResponseWillThrowNotFoundException(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2020-01-01');
        self::assertResponseStatusCodeSame(404);
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('message', $responseData);
        $this->assertEquals('Rates for date "2020-01-01" not found', $responseData['message']);
    }

    public function testResponseHasAllCurrencies(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates/2023-11-10');
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertCount(5, $responseData['currencies']);
        $this->assertEquals('USD', $responseData['currencies'][0]['code']);
        $this->assertEquals('EUR', $responseData['currencies'][1]['code']);
        $this->assertEquals('CZK', $responseData['currencies'][2]['code']);
        $this->assertEquals('BRL', $responseData['currencies'][3]['code']);
        $this->assertEquals('IDR', $responseData['currencies'][4]['code']);
    }
}