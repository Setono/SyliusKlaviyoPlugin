<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class TrackIdentifyClient implements TrackIdentifyClientInterface
{
    private RestClientInterface $httpClient;

    private SerializerInterface $serializer;

    public function __construct(
        RestClientInterface $httpClient,
        SerializerInterface $serializer,
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
    }

    public function trackEvent(Event $event): void
    {
        $json = $this->serializer->serialize($event, 'json', [
            'groups' => 'setono:sylius-klaviyo:event',
            AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
        ]);

        $response = $this->httpClient->post('events', [
            'data' => json_decode($json, true),
        ]);

        Assert::same($response->getStatusCode(), 202);
    }
}
