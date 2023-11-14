<?php

namespace Unit\Http;

use App\Http\NbpApiClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class NbpApiClientTest extends TestCase
{
    private $httpClient;
    private $cache;

    public function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->cache = $this->createMock(CacheInterface::class);
    }

    public function testFetch(): void
    {
        $this->cache->expects($this->once())
            ->method('get')
            ->willReturnCallback(function (string $cacheKey, callable $callback) {
                return $callback($this->createMock(ItemInterface::class));
            });

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($this->createMock(ResponseInterface::class));

        $apiClient = new NbpApiClient($this->httpClient, $this->cache);
        $apiClient->fetch('http://api.nbp.pl');
    }
}