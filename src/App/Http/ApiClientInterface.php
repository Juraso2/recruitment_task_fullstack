<?php

namespace App\Http;

interface ApiClientInterface
{
    public function fetch(string $url): array;
}