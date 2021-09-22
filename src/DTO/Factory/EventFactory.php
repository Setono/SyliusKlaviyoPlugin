<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Factory;

use Setono\ClientId\Provider\ClientIdProviderInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\Properties;

final class EventFactory implements EventFactoryInterface
{
    private ClientIdProviderInterface $clientIdProvider;

    public function __construct(ClientIdProviderInterface $clientIdProvider)
    {
        $this->clientIdProvider = $clientIdProvider;
    }

    public function create(Properties $properties): Event
    {
        $event = new Event($properties);
        $event->customerProperties->id = (string) $this->clientIdProvider->getClientId();

        return $event;
    }
}
