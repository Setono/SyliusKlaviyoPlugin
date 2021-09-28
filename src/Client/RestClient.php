<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class RestClient implements RestClientInterface
{
    private HttpClientInterface $httpClient;

    private string $baseUri;

    private string $token;

    public function __construct(HttpClientInterface $httpClient, string $baseUri, string $token)
    {
        $this->httpClient = $httpClient;
        $this->baseUri = rtrim($baseUri, '/');
        $this->token = $token;
    }

    public function get(string $endpoint): array
    {
        $endpoint = trim($endpoint, '/');

        return $this->httpClient->request(
            'GET',
            sprintf('%s/%s?api_key=%s', $this->baseUri, $endpoint, $this->token),
            [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        )->toArray();
    }
}
