<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO;

use Setono\SyliusKlaviyoPlugin\DTO\Event;
use Setono\SyliusKlaviyoPlugin\DTO\ViewedProductProperties;

final class EventTest extends DTOTestCase
{
    protected function getDTO(): Event
    {
        $properties = new ViewedProductProperties();
        $properties->eventId = 'event_id';

        $event = new Event($properties);
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
