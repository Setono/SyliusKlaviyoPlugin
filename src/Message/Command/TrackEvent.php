<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Command;

use Setono\SyliusKlaviyoPlugin\DTO\Event;

final class TrackEvent
{
    private Event $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}
