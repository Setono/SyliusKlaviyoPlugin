<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Client;

use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Webmozart\Assert\Assert;

final class TrackIdentifyClient implements TrackIdentifyClientInterface
{
    public function __construct(private readonly RestClientInterface $httpClient, private readonly SerializerInterface $serializer)
    {
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
