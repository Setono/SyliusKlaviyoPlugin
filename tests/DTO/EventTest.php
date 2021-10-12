<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO;

use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\ViewedProductProperties;

final class EventTest extends DTOTestCase
{
    protected function getDTO(): Event
    {
        $properties = $this->propertiesFactory->create(ViewedProductProperties::class);
        $properties->eventId = 'event_id';

        $event = new Event($properties, $this->propertiesFactory->create(CustomerProperties::class));
        $event->token = 'public_token';
        $event->timestamp = 1631103497;

        return $event;
    }

    protected function getExpectedData(): array
    {
        return [
            'token' => 'public_token',
            'event' => 'Viewed Product',
            'customer_properties' => [
                '$consent' => [],
            ],
            'properties' => [
                'Categories' => [],
                '$event_id' => 'event_id',
            ],
            'time' => 1631103497,
        ];
    }
}
