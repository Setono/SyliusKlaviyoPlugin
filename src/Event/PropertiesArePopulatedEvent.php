<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Event;

use Setono\SyliusKlaviyoPlugin\DTO\Event;

final class PropertiesArePopulatedEvent
{
    public Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }
}
