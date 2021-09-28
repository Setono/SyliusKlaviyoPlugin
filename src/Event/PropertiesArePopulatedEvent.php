<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Event;

use Setono\SyliusKlaviyoPlugin\DTO\Event;

final class PropertiesArePopulatedEvent
{
    public Event $event;

    /**
     * A context related to the event. This could for example contain the order related to the 'Placed Order' event.
     * See the relevant subscribers to check what context is available
     *
     * @var array<string, mixed>
     */
    public array $context;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(Event $event, array $context = [])
    {
        $this->event = $event;
        $this->context = $context;
    }
}
