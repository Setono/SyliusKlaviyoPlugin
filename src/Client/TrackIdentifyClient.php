<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Webmozart\Assert\Assert;

final class TrackIdentifyClient implements TrackIdentifyClientInterface
{
    private HttpClientInterface $httpClient;

    private SerializerInterface $serializer;

    private string $baseUri;

    private string $token;

    public function __construct(HttpClientInterface $httpClient, SerializerInterface $serializer, string $baseUri, string $token)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->baseUri = rtrim($baseUri, '/');
        $this->token = $token;
    }

    public function trackEvent(Event $event): void
    {
        $event->token = $this->token;

        $json = $this->serializer->serialize($event, 'json', [
            'groups' => 'setono:sylius-klaviyo:event',
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
        ]);

        $response = $this->httpClient->request('POST', sprintf('%s/track', $this->baseUri), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => ['data' => $json],
        ]);

        Assert::same($response->getStatusCode(), 200);
        Assert::same($response->getContent(), '1');
    }
}
