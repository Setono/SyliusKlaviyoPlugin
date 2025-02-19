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
        $properties->value = 42;

        $event = new Event($properties, $this->propertiesFactory->create(CustomerProperties::class));
        $event->timestamp = 1631103497;

        return $event;
    }

    protected function getExpectedData(): array
    {
        return [
            'type' => 'event',
            'attributes' => [
                'profile' => [
                    'data' => [
                        'type' => 'profile',
                        'attributes' => [],
                    ],
                ],
                'metric' => [
                    'data' => [
                        'type' => 'metric',
                        'attributes' => [
                            'name' => 'Viewed Product',
                        ],
                    ],
                ],
                'unique_id' => 'event_id',
                'time' => date('c', 1631103497),
                'value' => 42.0,
                'properties' => [
                    'Categories' => [],
                ],
            ],
        ];
    }
}
