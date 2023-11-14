<?php

namespace App\Http;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpApiClient implements ApiClientInterface
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var CacheInterface
     */
    private $cache;

    public function __construct(HttpClientInterface $nbpClient, CacheInterface $cache)
    {
        $this->httpClient = $nbpClient;
        $this->cache = $cache;
    }

    public function fetch(string $url): array
    {
        $cacheKey = md5($url);
        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($url) {
            $item->expiresAt(new \DateTime('tomorrow'));

            return $this->httpClient->request('GET', $url)->toArray();
        });
    }
}