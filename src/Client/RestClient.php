<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class RestClient implements RestClientInterface
{
    private readonly string $baseUri;

    public function __construct(private readonly HttpClientInterface $httpClient, string $baseUri, private readonly string $token, private readonly string $revision = '2024-05-15')
    {
        $this->baseUri = rtrim($baseUri, '/');
    }

    public function get(string $endpoint): ResponseInterface
    {
        $endpoint = trim($endpoint, '/');

        return $this->httpClient->request(
            'GET',
            sprintf('%s/%s/', $this->baseUri, $endpoint),
            ['headers' => $this->getDefaultHeaders()],
        );
    }

    public function post(string $endpoint, array $data): ResponseInterface
    {
        $endpoint = trim($endpoint, '/');

        return $this->httpClient->request(
            'POST',
            sprintf('%s/%s/', $this->baseUri, $endpoint),
            [
                'headers' => $this->getDefaultHeaders(),
                'json' => $data,
            ],
        );
    }

    public function getDefaultHeaders(): array
    {
        return [
            'Authorization' => sprintf('Klaviyo-API-Key %s', $this->token),
            'Accept' => 'application/json',
            'Revision' => $this->revision,
        ];
    }
}
