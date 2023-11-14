<?php

namespace Application\ExchangeRates;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExchangeRatesTest extends WebTestCase
{
    public function testConnectivity(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates');
        self::assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertJson($response->getContent());
        $responseData = json_decode($response->getContent(), TRUE);
        $this->assertArrayHasKey('currencies', $responseData);
    }

    public function testResponseHasAllCurrencies(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/exchange-rates');
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