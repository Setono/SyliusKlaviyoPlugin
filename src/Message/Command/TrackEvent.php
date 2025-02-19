<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Command;

use Setono\SyliusKlaviyoPlugin\DTO\Event;

final class TrackEvent implements CommandInterface
{
    public function __construct(private readonly Event $event)
    {
    }

    public function getEvent(): Event
    {
        return $this->event;
    }
}
